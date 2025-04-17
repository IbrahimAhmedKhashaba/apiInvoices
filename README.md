# 🧾 Invoice Management API – Laravel

A RESTful API built with Laravel for managing invoices, clients, and billing operations. Designed to serve front-end apps or mobile clients through secure, scalable endpoints.

---

## 🚀 Features

- Token-based Authentication (Sanctum)
- Create, View, Update, and Delete Invoices
- Client Management Endpoints
- JSON Responses with Standard API Structure
- Error Handling & Validation
- API Resource Formatting

---

## 🛠️ Built With

- **Laravel**
- **PHP 8+**
- **MySQL**
- **Laravel Sanctum**
- **Postman (for testing)**

---

## 📦 Installation

```bash
git clone https://github.com/IbrahimAhmedKhashaba/apiInvoices.git
cd apiInvoices
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
