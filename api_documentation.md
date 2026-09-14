# Elope WM E-Commerce — Product Catalog & Variants API Documentation

**Base URL**: `http://127.0.0.1:8000/api/v1`  
**Headers Required**:


## 1. Category Endpoints

### `GET /api/v1/categories`
Fetch all active categories with product counts.

#### Response `200 OK`:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Bags",
      "slug": "bags",
      "description": null,
      "image": null,
      "status": "active",
      "sort_order": 0,
      "products_count": 2,
      "created_at": "2026-09-13T19:16:23.000000Z"
    },
    {
      "id": 2,
      "name": "Outerwear",
      "slug": "outerwear",
      "description": null,
      "image": null,
      "status": "active",
      "sort_order": 0,
      "products_count": 2,
      "created_at": "2026-09-13T19:16:23.000000Z"
    }
  ]
}
```

---

## 2. Product Catalog & Variants Endpoints

### `GET /api/v1/products`
List all active products with search, filtering, and sorting support.

#### Supported Query Parameters:
| Parameter | Type | Example | Description |
|---|---|---|---|
| `q` or `search` | String | `?q=Tote` | Search product name, SKU, description, category |
| `category` | String | `?category=Bags` | Filter by category name or slug |
| `maxPrice` | Number | `?maxPrice=100` | Filter products with base_price <= 100 |
| `sort` | String | `?sort=price-asc` | `featured`, `price-asc`, `price-desc`, `rating` |
| `per_page` | Integer | `?per_page=20` | Pagination size (Default: 50) |

#### Response `200 OK`:
```json
{
  "data": [
    {
      "id": "tote-field",
      "db_id": 1,
      "name": "Canvas Field Tote",
      "slug": "canvas-field-tote-tote-field",
      "sku": "tote-field",
      "category": "Bags",
      "icon": "tote",
      "plate": "#DCD3BC",
      "price": 68.00,
      "compareAt": null,
      "desc": "A twelve-ounce cotton canvas tote built for market runs and daily carry.",
      "colors": ["Olive", "Sand", "Charcoal"],
      "sizes": null,
      "stock": 14,
      "rating": 4.60,
      "reviews": 38,
      "variants": [
        {
          "id": 101,
          "sku": "tote-field-olive",
          "price": 68.00,
          "compare_price": null,
          "cost_price": 30.00,
          "weight": 1.2,
          "barcode": "89012345",
          "status": "active",
          "attributes": [
            {
              "attribute_id": 1,
              "attribute_name": "Color",
              "attribute_slug": "color",
              "value": "Olive"
            }
          ]
        }
      ],
      "is_featured": false,
      "is_active": true,
      "created_at": "2026-09-13T19:16:23.000000Z",
      "updated_at": "2026-09-13T19:16:23.000000Z"
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/v1/products?page=1",
    "last": "http://127.0.0.1:8000/api/v1/products?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 50,
    "to": 16,
    "total": 16
  }
}
```

---

### `GET /api/v1/products/{id}`
Fetch single product details by **DB ID**, **SKU**, or **Slug**.

#### Example: `GET /api/v1/products/tote-waxed`

#### Response `200 OK`:
```json
{
  "data": {
    "id": "tote-waxed",
    "db_id": 2,
    "name": "Waxed Canvas Tote",
    "slug": "waxed-canvas-tote-tote-waxed",
    "sku": "tote-waxed",
    "category": "Bags",
    "icon": "tote",
    "plate": "#C9A98A",
    "price": 78.00,
    "compareAt": 92.00,
    "desc": "Paraffin-waxed canvas sheds rain and wears in with a patina unique to its owner.",
    "colors": ["Rust", "Black"],
    "sizes": null,
    "stock": 9,
    "rating": 4.80,
    "reviews": 52,
    "variants": [],
    "is_featured": true,
    "is_active": true
  }
}
```

---

### `POST /api/v1/products`
Create a new product (Admin).

#### Request Body `JSON`:
```json
{
  "name": "Service Leather Boot",
  "sku": "boot-service-v2",
  "base_price": 210.00,
  "compare_price": 240.00,
  "short_description": "Full-grain leather uppers on a Goodyear-welted sole.",
  "brand_id": 1,
  "category_ids": [3],
  "icon": "boot",
  "plate": "#6B4227",
  "colors": ["Chestnut", "Black"],
  "sizes": ["8", "9", "10", "11"],
  "stock": 25,
  "is_featured": true
}
```

#### Response `210 Created`:
```json
{
  "data": {
    "id": "boot-service-v2",
    "db_id": 17,
    "name": "Service Leather Boot",
    "sku": "boot-service-v2",
    "category": "Footwear",
    "price": 210.00,
    "compareAt": 240.00,
    "colors": ["Chestnut", "Black"],
    "sizes": ["8", "9", "10", "11"],
    "stock": 25,
    "is_active": true
  }
}
```

---

### `PUT /api/v1/products/{id}`
Update an existing product by ID or SKU.

#### Request Body `JSON`:
```json
{
  "name": "Service Leather Boot (Updated)",
  "base_price": 225.00,
  "stock": 18
}
```

#### Response `200 OK`:
```json
{
  "data": {
    "id": "boot-service-v2",
    "db_id": 17,
    "name": "Service Leather Boot (Updated)",
    "price": 225.00,
    "stock": 18
  }
}
```

---

### `DELETE /api/v1/products/{id}`
Delete a product by ID or SKU.

#### Response `200 OK`:
```json
{
  "message": "Product deleted successfully"
}
```
