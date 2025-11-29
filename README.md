## Requirements

- PHP 8.0 or higher
- Composer
- PostgreSQL

## Installation

1. Install dependencies:

```bash
composer dump-autoload
```

2. Configure your database in `config/app.php`:

```php
'database' => [
    'driver' => 'pgsql',      // pgsql for PostgreSQL, mysql for MySQL
    'host' => 'localhost',
    'port' => 5432,           // 5432 for PostgreSQL, 3306 for MySQL
    'database' => 'your_database',
    'username' => 'postgres',
    'password' => 'your_password',
    'charset' => 'utf8',
]
```

3. **Quick Start** (Development Server):

```bash
cd public
php -S localhost:8000
```

Then visit `http://localhost:8000` in your browser.

## Directory Structure

```
├── app/
│   ├── Controllers/     # Your controllers
│   ├── Models/          # Your models (with plain SQL)
│   └── Views/           # Your views
├── config/
│   └── app.php         # Configuration
├── core/                # Framework core
│   ├── Database/        # Database layer
│   ├── Http/            # Request/Response
│   ├── Middleware/      # Auth and CSRF middleware
│   ├── Application.php  # Main app
│   ├── Router.php       # Router
│   ├── Route.php        # Route class
│   ├── Controller.php   # Base controller
│   └── Model.php        # Base model
├── public/
│   ├── .htaccess       # Apache rewrite rules
│   └── index.php       # Entry point
├── routes/
│   └── web.php         # Your routes
```

## License

MIT License

## Contributing

Feel free to submit issues and enhancement requests!
