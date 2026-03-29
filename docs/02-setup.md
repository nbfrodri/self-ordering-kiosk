# Setup Guide

## Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop/) installed and running
- Git (optional, for version control)

## Quick Start

### 1. Clone / Enter the project directory

```bash
cd self-ordering-kiosk
```

### 2. Start Docker containers

```bash
docker-compose up -d --build
```

This will:
- Build the PHP application container (with MongoDB extension)
- Start Nginx web server on port **8000**
- Start MySQL on port **3306**
- Start MongoDB on port **27017**
- Run `composer install` automatically
- Run database migrations and seeders

### 3. Access the application

| URL                        | Description              |
|----------------------------|--------------------------|
| http://localhost:8000/kiosk | Customer ordering kiosk  |
| http://localhost:8000/cocina | Kitchen display system  |

### 4. Useful commands

```bash
# View logs
docker-compose logs -f app

# Enter the PHP container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan migrate:fresh --seed

# Stop all containers
docker-compose down

# Stop and remove volumes (reset databases)
docker-compose down -v
```

## Local Development (Without Docker)

If you prefer running locally with XAMPP or similar:

### Requirements
- PHP >= 8.2 with extensions: pdo_mysql, mongodb
- Composer
- MySQL 8.0
- MongoDB 7.x
- [MongoDB PHP Driver](https://www.php.net/manual/en/mongodb.installation.php)

### Steps

```bash
# 1. Install dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Edit .env with your database credentials
#    DB_HOST=127.0.0.1
#    DB_DATABASE=kiosk_db
#    MONGO_HOST=127.0.0.1

# 4. Generate application key
php artisan key:generate

# 5. Run migrations and seed
php artisan migrate --seed

# 6. Start development server
php artisan serve
```

## Environment Variables

| Variable         | Default          | Description                |
|-----------------|------------------|----------------------------|
| `DB_CONNECTION` | `mysql`          | Primary database driver    |
| `DB_HOST`       | `mysql`          | MySQL host (Docker service)|
| `DB_PORT`       | `3306`           | MySQL port                 |
| `DB_DATABASE`   | `kiosk_db`       | MySQL database name        |
| `DB_USERNAME`   | `kiosk_user`     | MySQL user                 |
| `DB_PASSWORD`   | `kiosk_password` | MySQL password             |
| `MONGO_HOST`    | `mongodb`        | MongoDB host               |
| `MONGO_PORT`    | `27017`          | MongoDB port               |
| `MONGO_DATABASE`| `kiosk_analytics`| MongoDB database name      |

## Troubleshooting

### Container won't start
```bash
docker-compose down -v
docker-compose up -d --build
```

### Database connection refused
Wait 10-15 seconds after `docker-compose up` for MySQL to fully initialize, then:
```bash
docker-compose exec app php artisan migrate --seed
```

### MongoDB extension not found
The Docker setup includes the MongoDB PHP extension. For local development, install it via PECL:
```bash
pecl install mongodb
```
Then add `extension=mongodb` to your `php.ini`.
