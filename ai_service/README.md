# D4ily.1 AI Recommendation Service

## Setup

1.  Ensure Python 3.9+ is installed.
2.  Navigate to this directory:
    ```bash
    cd ai_service
    ```
3.  Install dependencies:
    ```bash
    pip install -r requirements.txt
    ```

## Running the Service

Start the FastAPI server:

```bash
python main.py
```

The service will run on `http://localhost:8000`.

## Endpoints

-   `POST /recommend/similar-items`: Get content-based recommendations.
-   `POST /recommend/for-user`: Get collaborative filtering recommendations.
