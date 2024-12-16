# Laravel News Aggregator Project

This project aggregates news articles from three different platforms (News API, Guardian API, and New York Times API) and exposes APIs to interact with the data.

---

## Setup Instructions

### 1. Prerequisites

Ensure you have the following installed:
- PHP 8.2^
- Composer
- Docker & Docker Compose
- Laravel

---

### 2. Environment Setup

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Update the `.env` file if needed. **API Keys** are already configured for news fetching:

   ```
   NEWS_API_KEY="0736c8a60aa248c0ab4efe6d89534eef"
   GUARDIAN_API_KEY="aafbb65b-f798-48a3-9deb-e16aac1176f1"
   NYT_API_KEY="A1RG7AH2LaA1w1x5cqa1J9oGjax3V85G"
   ```

---

### 3. Install Dependencies

Run the following command to install PHP dependencies:
```bash
composer install
```

---

### 4. Run Migrations

Run database migrations to create necessary tables:
```bash
php artisan migrate
```

---

### 5. Setup Docker Environment

1. Build and run the Docker containers:
   ```bash
   docker-compose up -d
   ```

2. The application will be available at:
   ```
   http://localhost
   ```

---

### 6. Generate Swagger API Documentation

Swagger documentation is set up using the **L5-Swagger** package.

To generate Swagger documentation:
```bash
php artisan l5-swagger:generate
```

Once generated, access the Swagger UI at:
```
http://localhost/api/documentation
```

---

### 7. Fetch Articles Using Command

The application includes a command to fetch news articles from the APIs:

Run the following command:
```bash
php artisan app:fetch-articles
```

This command consumes APIs using the provided keys and stores the articles in the database.

---

## API Documentation

1. **Swagger Documentation**: Located at:
    - `swagger_news_aggregator` (converted Postman collection to Swagger format)

2. **Postman Collection**: Import the collection `news-aggregator.postman_collection` from the project root directory.

3. **APIs Location**:
    - All API routes are defined in `routes/api.php`.

---

## Installation Summary

1. Install Composer dependencies:
   ```bash
   composer install
   ```

2. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

4. Start Docker environment:
   ```bash
   docker-compose up -d
   ```

5. Generate Swagger docs:
   ```bash
   php artisan l5-swagger:generate
   ```

6. Fetch articles (optional):
   ```bash
   php artisan app:fetch-articles
   ```

---

## Notes

- **Docker**: Simplifies environment setup and allows consistent development.
- **Swagger**: API documentation is automatically generated and available at `/api/documentation`.
- **Postman**: Use the provided collection for testing APIs quickly.

For More information Contact me at: arsalanayoub49@gmail.com! 🚀
