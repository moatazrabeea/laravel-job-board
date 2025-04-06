# 🧑‍💼 Laravel Job Board API – Backend Assessment

This project is a backend assessment built with Laravel that exposes a single powerful API endpoint:

> `GET /api/jobs`

It supports dynamic, nested, and complex filtering over job listings, including EAV (Entity-Attribute-Value) attributes and many-to-many relationships.

---

## ⚙️ Tech Stack

- Laravel 12+
- MySQL
- RESTful API Design

---

## 🛠️ Setup Instructions

1. **Clone the repo**

```bash
git clone https://github.com/moatazrabeea/laravel-job-board.git
cd laravel-job-board


2. Install dependencies
composer install

3. Copy environment file & generate app key
cp .env.example .env
php artisan key:generate

4. Configure your .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

5. Run migrations and seed the database
php artisan migrate --seed

6. Start the development server
php artisan serve


##🔍 API Usage
GET /api/jobs
Returns a list of jobs. Supports advanced filtering via the filter query parameter.

✅ Sample Request
GET /api/jobs?filter=(job_type=full-time OR job_type=contract) AND languages HAS_ANY (PHP,Laravel) AND attribute:remote_friendly=true



##🔎 Notes
- No authentication is required.

- Only one endpoint (GET /api/jobs) is implemented.

- Clean and scalable filtering logic using a service class.

- EAV system allows for extensible custom job attributes.

## 📭 Postman Collection

No Postman collection is provided as this project contains only one API route (`GET /api/jobs`).  
You can easily copy the route and paste it into Postman using your local setup (`http://localhost:8000/api/jobs`) to test filtering functionality after deployment.

## Seeder Details

All necessary data is seeded automatically:

- **Attributes** of various types (`text`, `number`, `boolean`, `date`, `select`) are inserted via `AttributeSeeder`.
- **Job Listings** are created and have attributes attached with meaningful values in `JobListingSeeder`.

📌 Note: There's no separate seeder for `job_attribute_values` — values are set directly via Eloquent relationships using the `attach()` method on job listings.

```php
$job->attributes()->attach([
    1 => ['value' => 5],
    2 => ['value' => true],
    ...
]);
