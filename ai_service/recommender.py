import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from database import get_db_connection

def get_content_based_recommendations(product_id, top_n=5):
    conn = get_db_connection()
    query = "SELECT id, name, description, brand, category_id FROM products"
    df = pd.read_sql(query, conn)
    conn.close()

    if df.empty:
        return []

    # Combine features for similarity
    df['content'] = df['name'] + " " + df['description'] + " " + df['brand']
    
    tfidf = TfidfVectorizer(stop_words='english')
    tfidf_matrix = tfidf.fit_transform(df['content'])

    cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)

    indices = pd.Series(df.index, index=df['id']).drop_duplicates()

    if product_id not in indices:
        return []

    idx = indices[product_id]
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    sim_scores = sim_scores[1:top_n+1]
    
    product_indices = [i[0] for i in sim_scores]
    
    return df['id'].iloc[product_indices].tolist()

def get_user_content_recommendations(user_id, top_n=5):
    """
    Get recommendations for a user based on content filtering using their viewed products
    """
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
        recommendations = set()

        # Get similar products for each viewed product
        for product_id in viewed_products:
            similar = get_content_based_recommendations(product_id, min(3, top_n))
            recommendations.update(similar)

        # Remove products user has already viewed
        query = "SELECT product_id FROM interactions WHERE user_id = ?"
        cursor.execute(query, (user_id,))
        viewed_ids = set(row[0] for row in cursor.fetchall())

        recommendations = list(recommendations - viewed_ids)

        if len(recommendations) < top_n:
            # Get additional popular products to fill the gap
            query = """
                SELECT p.id
                FROM products p
                LEFT JOIN interactions i ON p.id = i.product_id
                WHERE p.id NOT IN ({})
                GROUP BY p.id
                ORDER BY COUNT(i.id) DESC
                LIMIT ?
            """.format(','.join(['?' for _ in viewed_products] + [top_n - len(recommendations)]))

            cursor.execute(query, viewed_products + [top_n - len(recommendations)])
            additional = [row[0] for row in cursor.fetchall()]
            recommendations.extend(additional)

        product_ids = recommendations[:top_n]

    conn.close()
    return product_ids
