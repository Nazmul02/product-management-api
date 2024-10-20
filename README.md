# Product Management API

This project is a simple product management API built using Laravel and Sanctum for authentication. It allows users to create, update, view, and delete products.

## Features

- User registration, login, and password reset via API.
- Authenticated users can create, update, and delete their products.
- Public product listing available for guests and authenticated users.
- Product image upload (multiple images). (Added additional)
- API request validation with appropriate HTTP status codes.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/product-management-api.git

2. Navigate to the project directory:
    ```bash
    cd product-management-api

3. Install the dependencies:

    ```bash
    composer install
4. Copy .env.example to .env:

    ```bash
    cp .env.example .env

5. Generate the application key:

    ```bash
    php artisan key:generate

6. Configure your .env file (e.g., database settings).

7. Run the migrations:

    ```bash
    php artisan migrate
8. Start the development server:

    ```bash
    php artisan serve
   
9. Run the PHPUnit tests:

    ```bash
    php artisan test
