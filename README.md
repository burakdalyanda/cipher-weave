# CipherWeave

CipherWeave is a Laravel middleware package designed to encrypt outgoing responses and decrypt incoming requests, ensuring your data remains secure throughout its journey.

## Features

- **Seamless Integration**: Easily integrate CipherWeave into your Laravel project.
- **Modern PHP & Laravel**: Built for PHP 8.2+ and Laravel 10, 11, and 12.
- **Encryption & Decryption**: Automatically encrypt responses and decrypt requests.
- **Configurable Security**: Customize encryption settings to fit your security needs.
- **Artisan Command**: Easily generate and set your encryption key.
- **Facade Support**: Simple access to encryption methods via `CipherWeave` Facade.

## Requirements

- PHP 8.2 or higher
- Laravel 10.x, 11.x or 12.x

## Installation

To install CipherWeave, use Composer:

```bash
composer require burakdalyanda/cipher-weave
```

## Configuration

Publish the configuration file to customize encryption settings:

```bash
php artisan vendor:publish --tag=cipherweave-config
```

This will create a `config/cipher.php` file. 

### Generate Encryption Key

You must generate an encryption key for the package to work:

```bash
php artisan cipherweave:key
```

This command will generate a key and set it in your `.env` file as `CIPHER_KEY`.

## Usage

### Register Middleware

#### Laravel 11 & 12
Add the middleware in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'encrypt.request.response' => \BurakDalyanda\CipherWeave\Middleware\EncryptRequestResponse::class,
    ]);
})
```

#### Laravel 10
Add the middleware to your `app/Http/Kernel.php` file:

```php
protected $routeMiddleware = [
    // Other middleware
    'encrypt.request.response' => \BurakDalyanda\CipherWeave\Middleware\EncryptRequestResponse::class,
];
```

### Protect Routes
Apply the middleware to your routes in `routes/web.php` or `routes/api.php`:

```php
Route::middleware(['encrypt.request.response'])->group(function () {
    Route::get('/secure-endpoint', [SecureController::class, 'index']);
});
```

#### Using a Custom Encryption Key for Specific Routes

You can override the default encryption key for specific routes:

```php
Route::middleware(['encrypt.request.response:your-custom-base64-key'])->group(function () {
    Route::get('/secure-endpoint', [SecureController::class, 'index']);
});
```

### Facade Usage

You can use the `CipherWeave` facade for manual encryption and decryption:

```php
use BurakDalyanda\CipherWeave\Facades\CipherWeave;

$encrypted = CipherWeave::encrypt('secret message');
$decrypted = CipherWeave::decrypt($encrypted);
```

### Manual Usage (DI)

You can also inject `CipherWeaveInterface`:

```php
use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;

public function __construct(private CipherWeaveInterface $cipher) {}

public function someMethod() {
    $encrypted = $this->cipher->encrypt(['data' => 'to encrypt']);
}
```

## Contributing

Contributions are welcome! Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for more details.

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
