# Laravel Rest Boilerplate

This repository provides a **clean and scalable boilerplate** for
Laravel 12 applications.\
It follows **best practices** by separating concerns, applying the
**Repository Pattern**, and using **Data Transfer Objects (DTOs)** for
structured data handling.

------------------------------------------------------------------------

## 🚀 Features

-   **Laravel 12** -- latest version of the framework.
-   **Service Layer** -- isolates business logic from controllers.
-   **Repository Pattern** -- abstracts database persistence and makes
    the code more testable.
-   **Rate Limiter** -- Rate Limiter is implemented to throttle user/IP 
    requests and prevent abuse or brute-force attacks. 
-   **DTOs (Data Transfer Objects)** -- ensures consistent and validated
    data transfer between layers.
-   **Clean Architecture Principles** -- improves maintainability and scalability.
-   **Laravel Sanctum with JWT** -- authentication ready for **REST API only**.
-   **Laravel Pint** -- code formatting tool to enforce coding style.
-   **PHPStan (Level 8)** -- static analysis tool to detect errors and
    enforce type safety.

------------------------------------------------------------------------

## 📂 Project Structure

    app/
     ├── Dto/                 # Data Transfer Objects
     ├── Http/
     │    ├── Controllers/    # Controllers only handle requests/responses
     ├── Repositories/        # Repository interfaces and implementations
     ├── Services/            # Business logic layer
     └── Models/              # Eloquent models

------------------------------------------------------------------------

## 🛠 How it works

1.  **Controllers**\
    Receive HTTP requests and delegate the work to the Services.\
    Controllers should stay thin and only handle request/response logic.

2.  **Services**\
    Contain the **business logic** of the application.\
    They orchestrate repositories, DTOs, and other components.

3.  **Repositories**\
    Abstract the persistence logic (database queries, Eloquent, etc.).\
    This allows you to swap the persistence layer with minimal effort.

4.  **DTOs**\
    Provide a structured way of passing validated data between layers.\
    Example: converting request data into a `UserDto` before passing it
    to a Service.

------------------------------------------------------------------------

## ✅ Example Flow

    Request → Controller → DTO → Service → Repository → Database

-   **Controller**: Converts request data into a DTO.\
-   **DTO**: Validates and standardizes input.\
-   **Service**: Applies business rules.\
-   **Repository**: Persists or retrieves data.

------------------------------------------------------------------------

## 📦 Installation

``` bash
git clone https://github.com/WilsonRU/laravel-rest-boilerplate.git
cd laravel-rest-boilerplate
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

------------------------------------------------------------------------

## 🔑 Authentication

This boilerplate comes with **Laravel Sanctum** configured to work with
**JWT tokens**.\
It is designed for **REST API only**, without frontend scaffolding.


    POST /api/core/login 200


``` json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30",
    "user": {
        "id": 1,
        "name": "Teste",
        ...
    }
}
```
------------------------------------------------------------------------

## 🧪 Testing

This boilerplate is designed to be **easily testable**.\
- Services and Repositories can be unit tested in isolation.\
- DTOs ensure consistent test data.

Run tests with:

``` bash
php artisan test
```

------------------------------------------------------------------------

## 🖌 Code Quality

- **Laravel Pint**: Apply coding style formatting

``` bash
    composer pint
```

- **PHPStan Level 8**: Run static analysis to catch errors early

``` bash
    composer stan
```