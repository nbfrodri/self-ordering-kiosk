# API Reference

Base URL: `http://localhost:8000/api`

All responses are JSON. All request bodies should be `Content-Type: application/json`.

---

## Menu

### GET /api/menu

Returns the full menu with categories, products, and available customizations.

**Response 200:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Burgers",
      "description": "Our signature burgers",
      "display_order": 1,
      "products": [
        {
          "id": 1,
          "name": "Classic Burger",
          "description": "Beef patty with lettuce, tomato, and our special sauce",
          "price": "5.99",
          "preparation_time_minutes": 5,
          "customizations": [
            {
              "id": 1,
              "name": "Onion",
              "type": "remove",
              "price_modifier": "0.00"
            },
            {
              "id": 7,
              "name": "Extra Cheese",
              "type": "add",
              "price_modifier": "1.00"
            }
          ]
        }
      ]
    }
  ]
}
```

---

## Orders (Kiosk)

### POST /api/pedidos

Create a new order.

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "product_name": "Classic Burger",
      "quantity": 2,
      "modifications": {
        "remove": ["Onion", "Pickles"],
        "add": ["Extra Cheese"]
      },
      "unit_price": 5.99,
      "subtotal": 13.98
    },
    {
      "product_id": 6,
      "product_name": "French Fries",
      "quantity": 1,
      "modifications": {
        "size": ["Large Size"]
      },
      "unit_price": 3.49,
      "subtotal": 4.99
    }
  ],
  "payment_method": "credit_card",
  "subtotal": 18.97,
  "tax": 1.52,
  "total": 20.49,
  "customer_name": "John",
  "notes": "Extra napkins please"
}
```

**Response 201:**
```json
{
  "message": "Order placed successfully",
  "data": {
    "order_number": "ORD-1042",
    "status": "pending",
    "items": [...],
    "estimated_preparation_minutes": 10,
    "created_at": "2026-03-28T12:30:00.000Z"
  }
}
```

**Validation Rules:**
- `items` — required, array, min 1
- `items.*.product_name` — required, string
- `items.*.quantity` — required, integer, min 1
- `payment_method` — required, one of: cash, credit_card, debit_card, mobile_pay
- `subtotal` — required, numeric, min 0
- `tax` — required, numeric, min 0
- `total` — required, numeric, min 0

### GET /api/pedidos/{orderNumber}/estado

Get the current status of an order.

**Response 200:**
```json
{
  "data": {
    "order_number": "ORD-1042",
    "status": "preparing",
    "estimated_preparation_minutes": 10,
    "created_at": "2026-03-28T12:30:00.000Z",
    "updated_at": "2026-03-28T12:32:00.000Z"
  }
}
```

**Response 404:**
```json
{
  "message": "Order not found"
}
```

### GET /api/pedidos

List all orders (paginated, 15 per page).

**Query Parameters:**
- `page` — Page number (default: 1)

---

## Kitchen Display

### GET /api/cocina/pedidos-pendientes

Get all active orders (status: pending, preparing, or ready).

**Response 200:**
```json
{
  "data": [
    {
      "order_number": "ORD-1042",
      "status": "pending",
      "customer_name": "John",
      "items": [
        {
          "product_name": "Classic Burger",
          "quantity": 2,
          "modifications": {
            "remove": ["Onion", "Pickles"],
            "add": ["Extra Cheese"]
          }
        }
      ],
      "notes": "Extra napkins please",
      "estimated_preparation_minutes": 10,
      "created_at": "2026-03-28T12:30:00.000Z"
    }
  ]
}
```

### PATCH /api/cocina/pedidos/{orderNumber}/estado

Update an order's status.

**Request Body:**
```json
{
  "status": "preparing"
}
```

**Valid Status Transitions:**
| Current Status | Allowed Next Status       |
|---------------|---------------------------|
| pending       | preparing, cancelled       |
| preparing     | ready, cancelled           |
| ready         | delivered                  |

**Response 200:**
```json
{
  "message": "Order status updated",
  "data": {
    "order_number": "ORD-1042",
    "status": "preparing",
    "updated_at": "2026-03-28T12:35:00.000Z"
  }
}
```

**Response 422:**
```json
{
  "message": "Invalid status transition from 'pending' to 'ready'"
}
```

---

## Analytics

### POST /api/analiticas/eventos

Log an analytics event.

**Request Body:**
```json
{
  "event_type": "category_viewed",
  "data": {
    "category_id": 1,
    "category_name": "Burgers",
    "time_spent_seconds": 45
  },
  "session_id": "sess_abc123"
}
```

**Response 201:**
```json
{
  "message": "Event logged"
}
```

**Common Event Types:**
- `category_viewed` — Customer browsed a category
- `product_viewed` — Customer opened a product detail
- `item_added_to_cart` — Item added to cart
- `item_removed_from_cart` — Item removed from cart
- `order_placed` — Order completed
- `order_cancelled` — Order cancelled
- `payment_screen_viewed` — Customer reached payment step
- `preparation_started` — Kitchen started an order
- `preparation_completed` — Kitchen completed an order

### GET /api/analiticas/resumen

Get analytics summary.

**Response 200:**
```json
{
  "data": {
    "total_orders": 156,
    "total_revenue": 2340.50,
    "average_order_value": 15.00,
    "average_preparation_minutes": 7.5,
    "orders_by_status": {
      "pending": 3,
      "preparing": 5,
      "ready": 2,
      "delivered": 140,
      "cancelled": 6
    },
    "popular_items": [
      { "product_name": "Classic Burger", "total_ordered": 89 },
      { "product_name": "French Fries", "total_ordered": 76 }
    ],
    "revenue_by_payment_method": {
      "credit_card": 1200.00,
      "cash": 800.50,
      "debit_card": 240.00,
      "mobile_pay": 100.00
    }
  }
}
```
