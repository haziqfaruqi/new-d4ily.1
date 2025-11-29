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

def get_collaborative_recommendations(user_id, top_n=5):
    conn = get_db_connection()
    # Fetch interactions (views, clicks, purchases)
    query = "SELECT user_id, product_id, type FROM interactions WHERE user_id IS NOT NULL"
    df = pd.read_sql(query, conn)
    conn.close()

    if df.empty:
        return []

    # Assign weights to interaction types
    weights = {'view': 1, 'click': 2, 'cart': 3, 'purchase': 5}
    df['weight'] = df['type'].map(weights)

    # Create User-Item Matrix
    user_item_matrix = df.pivot_table(index='user_id', columns='product_id', values='weight', aggfunc='sum').fillna(0)

    if user_id not in user_item_matrix.index:
        return []

    # Simple User-Based Collaborative Filtering (using Cosine Similarity)
    user_sim = cosine_similarity(user_item_matrix)
    user_sim_df = pd.DataFrame(user_sim, index=user_item_matrix.index, columns=user_item_matrix.index)

    similar_users = user_sim_df[user_id].sort_values(ascending=False)[1:6] # Top 5 similar users
    
    recommended_products = {}
    
    for similar_user in similar_users.index:
        user_products = user_item_matrix.loc[similar_user]
        for product_id, weight in user_products.items():
            if weight > 0 and user_item_matrix.loc[user_id, product_id] == 0: # Not interacted yet
                if product_id not in recommended_products:
                    recommended_products[product_id] = 0
                recommended_products[product_id] += weight * similar_users[similar_user]
    
    sorted_recs = sorted(recommended_products.items(), key=lambda x: x[1], reverse=True)
    return [x[0] for x in sorted_recs[:top_n]]
