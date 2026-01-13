# Task API

Multi-vendor order system API built with Laravel. This project provides a robust backend for managing products, orders, and user authentication.

## Features

- User Authentication (Login, Register, Logout) using Laravel Sanctum
- Role-based access control (Admin, User)
- Product Management
- Order Processing
- Email Notifications for Store Owners

## Installation

1. Clone the repository
2. Install dependencies:
   ```
   composer install
   ```
3. Copy the environment configuration:
   ```
   cp .env.example .env
   ```
4. Generate the application key:
   ```
   php artisan key:generate
   ```
5. Configure your database settings in the `.env` file.
6. Run database migrations:
   ```
   php artisan migrate
   ```
7. Start the development server:
   ```
   php artisan queue:work
   php artisan serve
   ```

## API Endpoints

### Authentication

- **POST** `/api/register` - Register a new user
- **POST** `/api/login` - Authenticate a user and return a token
- **POST** `/api/logout` - Invalidate the current token

## Testing

Run the test suite using PHPUnit:
```
php artisan test
```

## Security

If you discover any security related issues, please report them to the maintainers.

## License

The functionality is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
