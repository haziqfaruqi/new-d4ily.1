import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from database import get_db_connection
import threading
import time

# Global cache for TF-IDF data
_cache = {
    'tfidf_matrix': None,
    'cosine_sim': None,
    'indices': None,
    'df': None,
    'last_refresh': None,
    'lock': threading.Lock()
}

# Refresh cache every 5 minutes
CACHE_TTL = 300  # seconds

def _refresh_cache():
    """Refresh the cached TF-IDF matrix and similarity scores."""
    with _cache['lock']:
        conn = get_db_connection()
        query = "SELECT id, name, description, brand, category_id FROM products WHERE stock > 0"
        df = pd.read_sql(query, conn)
        conn.close()

        if df.empty:
            return

        # Combine features for similarity
        df['content'] = df['name'].fillna('') + " " + df['description'].fillna('') + " " + df['brand'].fillna('')

        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(df['content'])

        cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)

        indices = pd.Series(df.index, index=df['id']).drop_duplicates()

        # Update cache
        _cache['tfidf_matrix'] = tfidf_matrix
        _cache['cosine_sim'] = cosine_sim
        _cache['indices'] = indices
        _cache['df'] = df
        _cache['last_refresh'] = time.time()

def _ensure_cache():
    """Ensure cache is initialized and fresh."""
    if _cache['cosine_sim'] is None or _cache['last_refresh'] is None:
        _refresh_cache()
    elif time.time() - _cache['last_refresh'] > CACHE_TTL:
        # Refresh in background if stale, but use old cache for now
        def refresh_in_background():
            _refresh_cache()
        thread = threading.Thread(target=refresh_in_background)
        thread.start()

def get_content_based_recommendations(product_id, top_n=5):
    _ensure_cache()

    indices = _cache['indices']
    cosine_sim = _cache['cosine_sim']
    df = _cache['df']

    if product_id not in indices:
        return []

    idx = indices[product_id]
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    sim_scores = sim_scores[1:top_n+1]

    product_indices = [i[0] for i in sim_scores]

    return df['id'].iloc[product_indices].tolist()

def get_brand_category_recommendations(product_id, top_n=5):
    """
    Get recommendations with intelligent brand and category matching.

    Strategy:
    1. First prioritize: Same brand + same category (exact match)
    2. Second: Same brand within category (brand similarity with category match)
    3. Third: Similar brand names within category
    4. Fourth: Same category only
    5. Fallback: Popular products from same category
    """
    conn = get_db_connection()

    # Get the product's details
    query = """
        SELECT id, name, description, brand, category_id, price, condition
        FROM products
        WHERE id = ?
    """
    cursor = conn.cursor()
    cursor.execute(query, (product_id,))
    result = cursor.fetchone()

    if not result:
        conn.close()
        return []

    prod_id, prod_name, prod_desc, prod_brand, prod_category, prod_price, prod_condition = result

    # Get all products for comparison
    query = """
        SELECT id, name, description, brand, category_id, price, condition
        FROM products
        WHERE id != ? AND stock > 0
    """
    cursor.execute(query, (product_id,))
    candidates = cursor.fetchall()
    conn.close()

    if not candidates:
        return []

    scored_products = []

    for candidate in candidates:
        cand_id, cand_name, cand_desc, cand_brand, cand_category, cand_price, cand_condition = candidate
        score = 0
        priority_boost = 0

        # PRIORITY 1: Exact match - Same brand AND same category
        if prod_brand and cand_brand == prod_brand and prod_category == cand_category:
            priority_boost = 200  # Highest priority
            score += priority_boost

        # PRIORITY 2: Similar brand names within same category (fuzzy matching)
        elif prod_brand and cand_brand and prod_category == cand_category:
            brand_similarity = calculate_string_similarity(prod_brand.lower(), cand_brand.lower())
            if brand_similarity > 0.6:  # 60% similar or more
                score += 100 + (brand_similarity * 40)  # Up to 140 points

        # PRIORITY 3: Same brand, different category
        elif prod_brand and cand_brand == prod_brand:
            score += 80

        # PRIORITY 4: Similar brand name, different category
        elif prod_brand and cand_brand:
            brand_similarity = calculate_string_similarity(prod_brand.lower(), cand_brand.lower())
            if brand_similarity > 0.7:
                score += 60 + (brand_similarity * 20)  # Up to 80 points

        # PRIORITY 5: Same category only
        elif prod_category == cand_category:
            score += 40

        # Bonus: Price similarity (within 25%)
        if prod_price and cand_price:
            price_diff = abs(prod_price - cand_price) / prod_price
            if price_diff <= 0.25:
                score += 15

        # Bonus: Same condition
        if prod_condition and cand_condition == prod_condition:
            score += 10

        # Bonus: Name keyword matching
        name_words = set(prod_name.lower().split())
        cand_name_words = set(cand_name.lower().split())
        word_overlap = len(name_words & cand_name_words)
        if word_overlap > 0:
            score += word_overlap * 5

        # Bonus: Description keyword matching (lower weight)
        if prod_desc and cand_desc:
            desc_words = set(prod_desc.lower().split())
            cand_desc_words = set(cand_desc.lower().split())
            desc_overlap = len(desc_words & cand_desc_words)
            if desc_overlap > 0:
                score += desc_overlap * 2

        if score > 0:
            scored_products.append((cand_id, score))

    # Sort by score descending
    scored_products.sort(key=lambda x: x[1], reverse=True)

    # Return top N product IDs
    return [product[0] for product in scored_products[:top_n]]


def calculate_string_similarity(str1, str2):
    """
    Calculate similarity between two strings using a simple approach.
    Returns a value between 0 and 1.
    """
    if not str1 or not str2:
        return 0

    # Simple approach: check if one string is contained in the other
    if str1 in str2 or str2 in str1:
        return 0.8

    # Calculate common prefix/suffix
    common_prefix = 0
    for i in range(min(len(str1), len(str2))):
        if str1[i] == str2[i]:
            common_prefix += 1
        else:
            break

    # Calculate Jaccard similarity for words
    words1 = set(str1.split())
    words2 = set(str2.split())

    if not words1 or not words2:
        return 0

    intersection = len(words1 & words2)
    union = len(words1 | words2)
    word_similarity = intersection / union if union > 0 else 0

    # Combine metrics
    similarity = (common_prefix / max(len(str1), len(str2)) * 0.3 +
                   word_similarity * 0.7)

    return similarity

def get_user_content_recommendations(user_id, top_n=5):
    """
    Get recommendations for a user based on content filtering using their viewed products
    Optimized to use cached cosine similarity matrix
    """
    _ensure_cache()

    conn = get_db_connection()

    # Get products the user has viewed
    query = """
        SELECT product_id FROM interactions
        WHERE user_id = ? AND type = 'view'
        ORDER BY created_at DESC
        LIMIT 5
    """
    cursor = conn.cursor()
    cursor.execute(query, (user_id,))
    viewed_products = [row[0] for row in cursor.fetchall()]

    if not viewed_products:
        # Fallback to popular products if user hasn't viewed anything
        query = """
            SELECT p.id
            FROM products p
            LEFT JOIN interactions i ON p.id = i.product_id
            GROUP BY p.id
            ORDER BY COUNT(i.id) DESC
            LIMIT ?
        """
        cursor.execute(query, (top_n,))
        product_ids = [row[0] for row in cursor.fetchall()]
    else:
        # Use cached matrix directly for batch lookups
        indices = _cache['indices']
        cosine_sim = _cache['cosine_sim']
        df = _cache['df']

        recommendations = {}

        # Get similar products for each viewed product using cached matrix
        for product_id in viewed_products:
            if product_id not in indices:
                continue
            idx = indices[product_id]
            sim_scores = list(enumerate(cosine_sim[idx]))
            sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
            sim_scores = sim_scores[1:4]  # Top 3 per viewed product

            for i, score in sim_scores:
                rec_id = df['id'].iloc[i]
                if rec_id not in recommendations:
                    recommendations[rec_id] = score
                else:
                    recommendations[rec_id] += score  # Boost score if recommended multiple times

        # Remove products user has already viewed
        query = "SELECT product_id FROM interactions WHERE user_id = ?"
        cursor.execute(query, (user_id,))
        viewed_ids = set(row[0] for row in cursor.fetchall())

        for vid in viewed_ids:
            recommendations.pop(vid, None)

        # Sort by aggregated score
        sorted_recs = sorted(recommendations.items(), key=lambda x: x[1], reverse=True)
        product_ids = [pid for pid, _ in sorted_recs[:top_n]]

        if len(product_ids) < top_n:
            # Get additional popular products to fill the gap
            placeholders = ','.join(['?' for _ in viewed_ids]) if viewed_ids else '0'
            query = f"""
                SELECT p.id
                FROM products p
                LEFT JOIN interactions i ON p.id = i.product_id
                WHERE p.id NOT IN ({placeholders})
                GROUP BY p.id
                ORDER BY COUNT(i.id) DESC
                LIMIT ?
            """
            params = list(viewed_ids) + [top_n - len(product_ids)]
            cursor.execute(query, params)
            additional = [row[0] for row in cursor.fetchall()]
            product_ids.extend(additional)

    conn.close()
    return product_ids
