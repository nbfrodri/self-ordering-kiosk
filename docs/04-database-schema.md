# Database Schema

## MySQL — Relational Data

### categories
| Column        | Type         | Constraints                |
|--------------|-------------|----------------------------|
| id           | BIGINT (PK) | Auto increment             |
| name         | VARCHAR(255) | NOT NULL                  |
| description  | TEXT         | NULLABLE                   |
| image_url    | VARCHAR(255) | NULLABLE                  |
| display_order| INTEGER      | DEFAULT 0                  |
| is_active    | BOOLEAN      | DEFAULT true               |
| created_at   | TIMESTAMP    |                            |
| updated_at   | TIMESTAMP    |                            |

### products
| Column                    | Type          | Constraints              |
|--------------------------|--------------|--------------------------|
| id                       | BIGINT (PK)  | Auto increment           |
| category_id              | BIGINT (FK)  | REFERENCES categories(id)|
| name                     | VARCHAR(255)  | NOT NULL                |
| description              | TEXT          | NULLABLE                 |
| price                    | DECIMAL(8,2)  | NOT NULL                |
| image_url                | VARCHAR(255)  | NULLABLE                |
| is_available             | BOOLEAN       | DEFAULT true             |
| is_featured              | BOOLEAN       | DEFAULT false            |
| display_order            | INTEGER       | DEFAULT 0                |
| card_size               | VARCHAR(255)   | DEFAULT 'normal'         |
| preparation_time_minutes | INTEGER       | DEFAULT 5                |
| created_at               | TIMESTAMP     |                          |
| updated_at               | TIMESTAMP     |                          |

### product_customizations
| Column         | Type         | Constraints                  |
|---------------|-------------|------------------------------|
| id            | BIGINT (PK) | Auto increment               |
| product_id    | BIGINT (FK) | REFERENCES products(id)      |
| name          | VARCHAR(255) | NOT NULL                    |
| type          | ENUM         | 'add', 'remove', 'size', 'side' |
| price_modifier| DECIMAL(8,2) | DEFAULT 0.00               |
| is_available  | BOOLEAN      | DEFAULT true                 |
| created_at    | TIMESTAMP    |                              |
| updated_at    | TIMESTAMP    |                              |

### payments
| Column                | Type         | Constraints                          |
|----------------------|-------------|--------------------------------------|
| id                   | BIGINT (PK) | Auto increment                       |
| order_number         | VARCHAR(255) | UNIQUE, NOT NULL                    |
| payment_method       | ENUM         | 'cash','credit_card','debit_card','mobile_pay' |
| subtotal             | DECIMAL(10,2)| NOT NULL                            |
| tax                  | DECIMAL(10,2)| NOT NULL                            |
| total                | DECIMAL(10,2)| NOT NULL                            |
| status               | ENUM         | 'pending','completed','failed','refunded' |
| transaction_reference| VARCHAR(255) | NULLABLE                            |
| created_at           | TIMESTAMP    |                                      |
| updated_at           | TIMESTAMP    |                                      |

### Entity Relationships

```
categories (1) ──── (N) products (1) ──── (N) product_customizations
```

---

## MongoDB — Document Data

### Collection: `orders`

```json
{
  "_id": "ObjectId(...)",
  "order_number": "ORD-1042",
  "status": "preparing",
  "payment_id": 45,
  "customer_name": "John",
  "notes": "Extra napkins please",
  "items": [
    {
      "product_id": 1,
      "product_name": "Classic Burger",
      "quantity": 2,
      "unit_price": 5.99,
      "subtotal": 13.98,
      "modifications": {
        "remove": ["Onion", "Pickles"],
        "add": ["Extra Cheese"]
      }
    },
    {
      "product_id": 6,
      "product_name": "French Fries",
      "quantity": 1,
      "unit_price": 3.49,
      "subtotal": 4.99,
      "modifications": {
        "size": ["Large Size"]
      }
    }
  ],
  "estimated_preparation_minutes": 10,
  "created_at": "2026-03-28T12:30:00.000Z",
  "updated_at": "2026-03-28T12:32:00.000Z"
}
```

**Status values:** `pending` → `preparing` → `ready` → `delivered` | `cancelled`

### Collection: `product_images`

Stores product images as base64-encoded binary data, served via the API.

```json
{
  "_id": "ObjectId(...)",
  "product_id": 1,
  "filename": "classic-burger.jpg",
  "mime_type": "image/jpeg",
  "data": "<base64-encoded image data>",
  "created_at": "2026-03-28T10:00:00.000Z",
  "updated_at": "2026-03-28T10:00:00.000Z"
}
```

### Collection: `analytics_events`

```json
{
  "_id": "ObjectId(...)",
  "event_type": "category_viewed",
  "session_id": "sess_abc123",
  "data": {
    "category_id": 1,
    "category_name": "Burgers",
    "time_spent_seconds": 45
  },
  "created_at": "2026-03-28T12:28:00.000Z",
  "updated_at": "2026-03-28T12:28:00.000Z"
}
```

**Common event_type values:**
- `category_viewed`, `product_viewed`
- `item_added_to_cart`, `item_removed_from_cart`
- `order_placed`, `order_cancelled`
- `payment_screen_viewed`
- `preparation_started`, `preparation_completed`

---

## Why This Split?

| Concern                  | MySQL | MongoDB | Reason                                           |
|-------------------------|-------|---------|--------------------------------------------------|
| Product catalog         | X     |         | Structured, relational (categories → products)   |
| Price data              | X     |         | Financial accuracy, ACID transactions             |
| Payment records         | X     |         | Accounting integrity, no data loss                |
| Order details           |       | X       | Flexible item modifications per order             |
| Customizations per order|       | X       | Variable structure: some items have 0, some have 5|
| Product images          |       | X       | Binary storage without filesystem dependency      |
| Analytics events        |       | X       | High-volume writes, flexible schema, fast inserts |
