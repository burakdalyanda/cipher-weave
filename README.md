# CipherWeave

CipherWeave is a Laravel middleware package designed to encrypt outgoing responses and decrypt incoming requests, ensuring your data remains secure throughout its journey.

## Features

- **Seamless Integration**: Easily integrate CipherWeave into your Laravel project.
- **Encryption & Decryption**: Automatically encrypt responses and decrypt requests.
- **Configurable Security**: Customize encryption settings to fit your security needs.

## Installation

To install CipherWeave, use Composer:

```bash
composer require burakdalyanda/cipher-weave
```

## Usage

### Register Middleware
Add the middleware to your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    // Other middleware
    'cipherweave' => \Dalyanda\CipherWeave\Middleware\CipherWeave::class,
];
```
### Protect Routes
Apply the middleware to your routes in routes/web.php or routes/api.php:

```php
Route::middleware(['cipherweave'])->group(function () {
    Route::get('/secure-endpoint', 'SecureController@index');
    // Other routes
});
```

### Configuration

Publish the configuration file to customize encryption settings:

```bash
php artisan vendor:publish --tag=cipherweave-config
```

This will create a config/cipherweave.php file where you can adjust settings like encryption algorithm, key length, etc.

## Contributing

Contributions are welcome! Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for more details on how to get involved.

## License

CipherWeave is open-sourced software licensed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.en.html).
