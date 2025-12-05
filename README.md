# 🛍️ Belanjakan — E-commerce Backend API

A backend RESTful API built for the Belanjakan mobile e-commerce application.  
Provides user authentication, product browsing, shopping cart, transactions, and reviews.

---

## 🚀 Features

- 🔐 User Authentication (OAuth2 using **Laravel Passport**)
- 🛍️ Product & Category Management
- 🛒 Cart System with CRUD Operations
- 💳 Checkout & Transaction Processing
- ⭐ Product Reviews
- ⚙️ Stock Updates & Admin-only Actions
- 📱 Supports Mobile App Integration

---

## 🛠️ Tech Stack

| Component | Technology |
|----------|------------|
| Framework | Laravel |
| Authentication | Laravel Passport |
| Database | MySQL |
| API Type | REST API |

---

## 📦 Installation & Setup

### Install dependencies
composer install

### Setup environment
cp .env.example .env
php artisan key:generate

### Configure DB connection in .env first

### Run database migration + passport installation
php artisan migrate
php artisan passport:install

### Start server
php artisan serve

---
## 🔌 API Endpoints

### 🧑‍💼 Authentication
| Method | Endpoint  | Description           |
| ------ | --------- | --------------------- |
| POST   | /register | Register a new user   |
| POST   | /login    | Login & receive token |
### 🛍️ Items
| Method | Endpoint                    | Description                    |
| ------ | --------------------------- | ------------------------------ |
| GET    | /items                      | Get all items                  |
| GET    | /items/{id}                 | Get item details               |
| GET    | /items/search/{id}/q={term} | Search item by name            |
| GET    | /items/cat/{id}             | Items by category              |
| POST   | /items                      | Create item *(auth required)*  |
| PUT    | /items/{id}                 | Update item *(auth required)*  |
| PATCH  | /items/{id}                 | Update stock *(auth required)* |
| DELETE | /items/{id}                 | Delete item *(auth required)*  |
### 📂 Categories
| Method | Endpoint         |
| ------ | ---------------- |
| GET    | /categories      |
| GET    | /categories/{id} |
| POST   | /categories      |
| PUT    | /categories/{id} |
| DELETE | /categories/{id} |
### ⭐ Reviews
| Method | Endpoint           |
| ------ | ------------------ |
| GET    | /item/{id}/reviews |
| POST   | /reviews           |
| GET    | /reviews/{id}      |
| PUT    | /reviews/{id}      |
| DELETE | /reviews/{id}      |
### 🛒 Cart
| Method | Endpoint    |
| ------ | ----------- |
| GET    | /carts      |
| POST   | /carts      |
| GET    | /carts/{id} |
| PUT    | /carts/{id} |
| DELETE | /carts/{id} |
### 💳 Transactions
| Method | Endpoint                   |
| ------ | -------------------------- |
| GET    | /transactions              |
| POST   | /transactions              |
| GET    | /transactions/{id}         |
| PUT    | /transactions/{id}         |
| GET    | /transactions/{id}/details |
| POST   | /transactions/details      |
| PATCH  | /transactions/details/{id} |
### 🏷️ Coupons
| Method | Endpoint    |
| ------ | ----------- |
| GET    | /coupons      |
| POST   | /coupons      |
| GET    | /coupons/{id} |
| PUT    | /coupons/{id} |
| DELETE | /coupons/{id} |
---
## 📄 License

This project is for educational purposes.
