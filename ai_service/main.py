from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Optional
import recommender

app = FastAPI()

class RecommendationRequest(BaseModel):
    user_id: Optional[int] = None
    product_id: Optional[int] = None
    limit: int = 5

@app.get("/")
def read_root():
    return {"message": "D4ily.1 AI Service is running"}

@app.post("/recommend/similar-items")
def similar_items(request: RecommendationRequest):
    if not request.product_id:
        raise HTTPException(status_code=400, detail="Product ID is required for similar items")
    
    try:
        product_ids = recommender.get_content_based_recommendations(request.product_id, request.limit)
        return {"product_ids": product_ids}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/recommend/for-user")
def for_user(request: RecommendationRequest):
    if not request.user_id:
        raise HTTPException(status_code=400, detail="User ID is required for user recommendations")

    try:
        product_ids = recommender.get_user_content_recommendations(request.user_id, request.limit)
        return {"product_ids": product_ids}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
