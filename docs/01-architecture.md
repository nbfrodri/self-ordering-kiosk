# Architecture Overview

## System Architecture

The Self-Ordering Kiosk is a full-stack application that mimics the ordering systems found in fast food restaurants. It consists of two user-facing interfaces backed by a Laravel REST API.

```
┌─────────────────────┐     ┌──────────────────────┐
│   KIOSK SCREEN      │     │  KITCHEN DISPLAY (KDS)│
│   (Customer)        │     │  (Kitchen Staff)      │
│                     │     │                       │
│  - Browse menu      │     │  - View pending orders│
│  - Customize items  │     │  - Update status      │
│  - Simulate payment │     │  - Track prep time    │
└────────┬────────────┘     └──────────┬────────────┘
         │                             │
         │     HTTP REST API           │
         └──────────┬──────────────────┘
                    │
         ┌──────────▼──────────────┐
         │   LARAVEL 12 API        │
         │                         │
         │  - MenuController       │
         │  - PedidoController     │
         │  - CocinaController     │
         │  - AnalyticsController  │
         └─────┬──────────┬────────┘
               │          │
       ┌───────▼──┐  ┌────▼─────────┐
       │  MySQL   │  │   MongoDB    │
       │          │  │              │
       │ Products │  │ Orders       │
       │ Payments │  │ Analytics    │
       │ Catalog  │  │ Customizat.  │
       └──────────┘  └──────────────┘
```

## Why Two Databases?

### MySQL (Relational — Structured Data)
- **Product catalog**: Categories, products, available customizations — structured and consistent
- **Payments**: Financial transactions require ACID compliance — no room for data loss
- **Referential integrity**: Foreign keys ensure categories have valid products

### MongoDB (Document — Flexible Data)
- **Orders with customizations**: Each order can have wildly different modification combinations. Storing `{"remove": ["onion", "pickles"], "add": ["extra cheese"]}` as a flexible JSON document is far simpler than creating relational bridge tables
- **Analytics events**: High-volume write operations for tracking user behavior — MongoDB handles this efficiently
- **Schema flexibility**: New event types or order fields can be added without migrations

## Docker Services

| Service  | Image        | Port  | Purpose                    |
|----------|-------------|-------|----------------------------|
| app      | PHP 8.2-FPM | -     | Laravel application        |
| nginx    | nginx:alpine| 8000  | Web server / reverse proxy |
| mysql    | mysql:8.0   | 3306  | Relational database        |
| mongodb  | mongo:7     | 27017 | Document database          |

## Request Flow Example

1. Customer taps "Double Cheeseburger" on kiosk
2. Selects "No Onion" and "Extra Cheese"
3. Taps "Place Order"
4. **POST /api/pedidos** →
   - Laravel validates the request
   - Creates `Payment` record in MySQL (subtotal, tax, total, method)
   - Creates `Order` document in MongoDB (items with full customization details)
   - Returns order number `ORD-1042`
5. Kitchen display polls **GET /api/cocina/pedidos-pendientes** every 5 seconds
6. Cook sees the order with clear instructions: "NO Onion | + Extra Cheese"
7. Cook taps "Start Preparing" → **PATCH /api/cocina/pedidos/ORD-1042/estado** `{status: "preparing"}`
8. When done → marks as "Ready"
