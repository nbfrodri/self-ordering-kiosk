# Self-Ordering Kiosk

A full-featured self-ordering kiosk system for fast-food restaurants, built with Laravel 12, MySQL, MongoDB, and Docker.

Includes a customer-facing ordering interface, kitchen display system, admin menu management panel, and an analytics dashboard.

## Tech Stack

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL_8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB_7-47A248?style=for-the-badge&logo=mongodb&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS_4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

## Features

- **Kiosk Interface** - Browse menu by category, customize items, place orders with multiple payment methods
- **Kitchen Display** - Real-time view of pending/preparing/ready orders with status updates
- **Admin Panel** - Full CRUD for categories, products, and customizations with image management
- **Analytics Dashboard** - Order metrics, popular items, average prep time, revenue by payment method
- **Dual Database Architecture** - MySQL for catalog & payments, MongoDB for orders & analytics
- **REST API** - Clean, documented endpoints for all functionality

## Prerequisites

- [Docker](https://www.docker.com/) & Docker Compose
- Or for local development: PHP 8.2+, Composer, Node.js, MySQL 8, MongoDB 7

## Quick Start (Docker)

```bash
# Clone the repository
git clone <repo-url>
cd self-ordering-kiosk

# Copy environment file
cp .env.example .env

# Start containers
docker compose up -d

# Install dependencies and set up the database
docker compose exec app composer setup

# Build frontend assets
docker compose exec app npm install
docker compose exec app npm run build
```

The application will be available at **http://localhost:8000**.

## Quick Start (Local)

```bash
# Install PHP and JS dependencies
composer install
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed data
php artisan migrate --seed

# Start the development server
composer dev
```

This runs the Laravel server, queue worker, log watcher, and Vite dev server concurrently.

## Application Routes

| URL           | Description                 |
| ------------- | --------------------------- |
| `/kiosk`      | Customer ordering interface |
| `/kitchen`    | Kitchen display system      |
| `/analytics`  | Analytics dashboard         |
| `/admin/menu` | Menu management panel       |

## API Endpoints

| Method  | Endpoint                                   | Description                |
| ------- | ------------------------------------------ | -------------------------- |
| `GET`   | `/api/menu`                                | Get full menu (cached)     |
| `POST`  | `/api/pedidos`                             | Create an order            |
| `GET`   | `/api/pedidos/{orderNumber}/estado`        | Get order status           |
| `GET`   | `/api/cocina/pedidos-pendientes`           | Get pending kitchen orders |
| `PATCH` | `/api/cocina/pedidos/{orderNumber}/estado` | Update order status        |
| `POST`  | `/api/analiticas/eventos`                  | Log analytics event        |
| `GET`   | `/api/analiticas/resumen`                  | Get analytics summary      |
| `*`     | `/api/admin/categories`                    | Categories CRUD            |
| `*`     | `/api/admin/products`                      | Products CRUD              |

Full API documentation is in [`docs/03-api-reference.md`](docs/03-api-reference.md).

## Database Architecture

- **MySQL** - Categories, products, customizations, payments (relational, ACID transactions)
- **MongoDB** - Orders, analytics events, and product images (flexible schema, binary storage)

See [`docs/04-database-schema.md`](docs/04-database-schema.md) for detailed schemas.

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Api/
│   │   ├── MenuController.php          # Menu retrieval
│   │   ├── PedidoController.php        # Order creation & status
│   │   ├── CocinaController.php        # Kitchen order management
│   │   ├── AnalyticsController.php     # Analytics events & summary
│   │   └── AdminMenuController.php     # Admin CRUD operations
│   └── PageController.php              # View rendering
├── Models/                             # Eloquent & MongoDB models
database/
├── migrations/                         # MySQL schema migrations
├── seeders/                            # Sample menu data
docker/
├── php/Dockerfile                      # PHP-FPM with MongoDB extension
├── nginx/default.conf                  # Nginx configuration
docs/                                   # Architecture, setup, API docs
resources/
├── views/                              # Blade templates
├── js/ & css/                          # Frontend assets
```

## Testing

```bash
php artisan test
```

## Documentation

Detailed documentation is available in the [`docs/`](docs/) directory:

1. [Architecture](docs/01-architecture.md)
2. [Setup Guide](docs/02-setup.md)
3. [API Reference](docs/03-api-reference.md)
4. [Database Schema](docs/04-database-schema.md)
5. [Seeded Menu](docs/05-seeded-menu.md)

## AI Disclosure

This project was developed with the assistance of AI tools, including [Claude](https://claude.ai/) by Anthropic for some code generation and documentation, and [Google Stitch](https://stitch.withgoogle.com/) for the frontend design.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
