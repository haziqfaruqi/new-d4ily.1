# Real-Time Recommendation System

## How It Works

### 1. **User Views a Product**
- When user visits a product detail page (e.g., Cardigan)
- Interaction logged: `type='view'` with weight 1.0
- System records: brand, category, color, condition, price

### 2. **User Returns to Shop Page**
- "Recommended For You" section shows products similar to viewed item
- Prioritizes: Same brand + Same category (Cardigans from that brand)
- Also shows: Other Cardigans (same category, different brands)

### 3. **User Clicks a Product on Shop Page**
1. `trackProductClick(productId)` is triggered
2. Interaction logged: `type='click'` with weight 2.0
3. **Immediate refresh** of recommendations section
4. New recommendations show:
   - Products from **same brand** as clicked item
   - Products from **same category** as clicked item
   - Products with **same color**
   - Products with **similar price**

## Example Flow

```
User clicks "H&M Cardigan Black"
  ↓
Recommendations refresh immediately showing:
  ↓
• Other H&M Cardigans (same brand + category) ← Highest priority
• Other H&M items (same brand)
• Other Cardigans from different brands (same category)
• Black items (same color)
• Items with similar price range
```

## Technical Implementation

### API Endpoint
**GET** `/api/recommendations/similar-products/{productId}?limit=8`

Returns products similar to the specified product, prioritized by:
1. Same brand + same category (100 points)
2. Same brand only (50 points)
3. Same category only (40 points)
4. Same color (+20 points)
5. Same condition (+10 points)
6. Similar price (+15 points)
7. Newness boost (up to +3 points)

### Frontend Flow
```javascript
trackProductClick(productId)
  ↓
Log interaction to /api/interactions
  ↓
Fetch similar products from /api/recommendations/similar-products/{productId}
  ↓
Update "Recommended For You" section (no page reload)
  ↓
Update title: "Similar Items You Might Like"
```

## Files Modified

1. **RecommendationController.php** - Added `similarProducts()` method
2. **api.php** - Added route for similar-products endpoint
3. **shop/index.blade.php** - Updated JavaScript to refresh recommendations on click
4. **ShopController.php** - View interactions already logged (lines 124-130)

## Priority Scoring System

| Match Type | Score | Example |
|------------|-------|---------|
| Same brand + Same category | 100 | H&M Cardigan → Another H&M Cardigan |
| Same brand only | 50 | H&M Cardigan → H&M Shirt |
| Same category only | 40 | H&M Cardigan → Zara Cardigan |
| Same color | +20 | Black item → Another Black item |
| Same condition | +10 | New → New |
| Similar price (±30%) | +15 | RM50 → RM35-RM65 range |
| Newness boost | +0-3 | Items < 30 days old get small boost |

## Visual Feedback

- Recommendations section title changes to "Similar Items You Might Like"
- Subtitle: "Based on the item you just clicked"
- Subtle pulse animation when updated
- No page reload needed
