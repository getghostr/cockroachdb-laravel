# cockroachdb-laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/getghostr/cockroachdb-laravel.svg?style=flat-square)](https://packagist.org/packages/getghostr/cockroachdb-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/getghostr/cockroachdb-laravel.svg?style=flat-square)](https://packagist.org/packages/getghostr/cockroachdb-laravel)


Simple CockroachDB driver for Laravel 6+

## Installation

You can install the package via composer:
```
composer require getghostr/cockroachdb-laravel
```

## Usage

### Step 1
If you're not using package discovery please add the following to your providers array in `config/app.php`:
```php
Ghostr\Cockroach\CockroachServiceProvider::class,
```

### Step 2
Head over to your `config/database.php` and add the following to your connections array:
```php
'cockroach' => [
    'driver' => 'cockroach',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'schema' => 'public',
    'sslmode' => 'prefer',
],
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Ghostr](https://github.com/GetGhostr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
