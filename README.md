# sk5656201

```php
php artisan queue:work
```

```php
npm run dev:all
```

```python
npm run clip
```

```md
## How to run (Mac)

1. Install dependencies (one time):

    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    ```

2. Start services:

    ```bash
    # Terminal 1 – Laravel
    php artisan serve

    # Terminal 2 – Queue
    php artisan queue:work

    # Terminal 3 – AI service
    cd python
    python -m uvicorn clip_service:app --host 127.0.0.1 --port 5055
    ```

3. Run a scan:

    ```bash
    php artisan discovery:run --marketplaces=US,UK,DE,FR,IT,ES
    ```

4. Open the dashboard:

    - Visit: http://127.0.0.1:8000/admin/dashboard/matches
    - Login using the credentials we provide.
```

----

- php artisan serve
- php artisan queue:work
- cd python && python -m uvicorn clip_service:app --host 127.0.0.1 --port 5055
- php artisan discovery:run --marketplaces=US,UK,DE,FR,IT,ES
- npm run dev:all

````markdown
// filepath: d:\Career\Freelance\sk5656201\Amazon-1688-Product-Scanner-Laravel\docs\PHASE1_SETUP.md
# Phase‑1 – Core System: Setup & Usage

## 1. Requirements
- PHP version ...
- Composer
- Node (optional for UI assets)
- MySQL / MariaDB
- Mac or Windows supported

## 2. Installation
1. Clone project.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and set:
   - DB_*
   - CLIP_SERVICE_URL
   - etc.
4. Run migrations:
   - `php artisan migrate`
5. (Optional) seed basic data.

## 3. Running the system
1. Start server:
   - `php artisan serve`
   - Base URL: `https://amazon-1688-product-scanner-laravel.test` (or `http://127.0.0.1:8000`)

2. Fetch Amazon best-sellers:
   - Endpoint: `POST /api/amazon/best-sellers/scan`
   - Body example:
     ```json
     {
       "marketplace": "US",
       "bestseller_url": "https://www.amazon.com/Best-Sellers/zgbs"
     }
     ```

3. Run 1688 matching pipeline:
   - Endpoint: `POST /api/matches/run`

4. View results dashboard:
   - URL: `/product/matches`
   - Columns:
     - Similarity, Amazon Title, Marketplace, Amazon Price,
     - 1688 Title, 1688 Min, Margin, Already?, Status, Action.

## 4. 1688 API
- Search endpoint: `POST /api/ali1688/search`
- Body:
  ```json
  { "q": "wireless mouse" }
  ```
- Internally uses a private scraper/proxy with session & headers.

## 5. Notes & Limitations
- Scraping depends on IP/proxies; heavy use may require rotating proxies.
- Matching accuracy comes from CLIP model via `CLIP_SERVICE_URL`.
- `already_on_amazon` is determined by ...
