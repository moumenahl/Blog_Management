## 📘 Laravel Blog API — Project Documentation

### 📌 Overview

This project is a **Laravel 12 RESTful API** for managing blog posts. It includes secure user authentication using **Laravel Sanctum**, robust data validation using **Form Requests**, and custom validation rules to ensure clean and consistent data. It also auto-generates slugs and meta descriptions when not provided by the user.

---

### ✅ Key Features

* User registration, login, and logout using **Laravel Sanctum**
* CRUD operations for blog posts
* Protected routes via `auth:sanctum` middleware
* Custom validation using `FormRequest` classes
* Automatic generation of `slug` and `meta_description` if not provided
* Custom validation rules:

  * Valid slug format
  * Future-dated publish date
  * Maximum of 5 keyword entries

---

### 🔐 Authentication Endpoints

| Method | Endpoint      | Description         |
| ------ | ------------- | ------------------- |
| POST   | /api/register | Register a new user |
| POST   | /api/login    | Log in user         |
| POST   | /api/logout   | Log out user        |

---

### ✏️ Blog Post Endpoints

| Method | Endpoint        | Description          |
| ------ | --------------- | -------------------- |
| GET    | /api/posts      | List all posts       |
| GET    | /api/posts/{id} | Show a specific post |
| POST   | /api/posts      | Create a new post    |
| PUT    | /api/posts/{id} | Update a post        |
| DELETE | /api/posts/{id} | Delete a post        |

All these routes are protected and require authentication via Sanctum.

---

### 📋 Validation Logic

Form requests used:

* `StorePostRequest`
* `UpdatePostRequest`

Validation features:

* **prepareForValidation()**:

  * Automatically generates `slug` from `title` if not provided
  * Limits `meta_description` to 255 characters
  * Converts `is_published` to boolean
  * Sets a default for `keywords` if not provided
    * If `meta_description` is missing, generate one using the title
    
* **passedValidation()**:

  * logging 

---

### 🛠 Custom Validation Rules

Custom rules created in `App\Rules`:

* `SlugValidation`: Ensures valid characters in the slug
* `FutureDate`: Ensures `publish_date` is a date in the future
* `KeywordsValidation`: Limits keywords to 5 space-separated words

These rules are injected in your `rules()` method of the Form Request.

---

### 📦 Sample Postman Request

#### Register:

```json
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

#### Create Post:

```json
POST /api/posts
Headers: Authorization: Bearer <token>
{
  "title": "Laravel Tips",
  "body": "This post shares Laravel tips.",
  "publish_date": "2025-06-01 10:00:00",
  "tags": "laravel,php,api",
  "keywords": "laravel blog tips"
}
```

If `slug` and `meta_description` are not included, they will be generated automatically.

---

### 🔒 Route Protection

All post routes are protected by Sanctum authentication.

---

### 🩵 Logging

We used Laravel’s logging in `passedValidation()`
You can find this log in `storage/logs/laravel.log`.

---
### ⚙️ Run Locally

```bash
git clone <your_repo>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

### Postman Collection

You can download the Postman collection for this project from the link below:

[Download Postman Collection](https://github.com/moumenahl/Blog_Management/raw/main/Blogs_Management.postman-collection.json)
