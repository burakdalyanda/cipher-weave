# CipherWeave

CipherWeave is a Laravel middleware package designed to encrypt outgoing responses and decrypt incoming requests, ensuring your data remains secure throughout its journey.

## Features

- **Seamless Integration**: Easily integrate CipherWeave into your Laravel project.
- **Encryption & Decryption**: Automatically encrypt responses and decrypt requests.
- **Configurable Security**: Customize encryption settings to fit your security needs.
- **Flexible Key Management**: Optionally override encryption keys for specific routes or manual operations.

## Requirements

- PHP 8.0 or higher
- Laravel 8.x or 9.x

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

This will create a `config/cipher.php` file where you can adjust settings such as encryption algorithm, key, and whether to disable encryption in debug mode.

Key Settings:

* The encryption key can be set in your .env file using CIPHER_KEY.
* The default encryption key is the APP_KEY defined in the .env file.
* Cipher algorithms supported: "`AES-128-CBC`", "`AES-256-CBC`".

Example Configuration:

```php
return [
    'key' => env('CIPHER_KEY', env('APP_KEY')),
    'cipher' => env('CIPHER_ALGORITHM', 'AES-128-CBC'),
    'initial_vector' => env('CIPHER_IV', '0123456789ABCDEF'),
    'disable_on_debug' => false,
];
```


## Usage

### Register Middleware
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
    Route::get('/secure-endpoint', 'SecureController@index');
    // Other routes
});
```

#### Using a Custom Encryption Key for Specific Routes

You can override the default encryption key for specific routes or route groups by passing a key parameter to the middleware:

```php
Route::middleware(['encrypt.request.response:custom_key'])->group(function () {
    Route::get('/secure-endpoint', 'SecureController@index');
    // Other routes
});
```

### Manual Encryption/Decryption

You can also manually encrypt or decrypt data using the CipherWeaveManual class:

```php
use BurakDalyanda\CipherWeave\CipherWeaveManual;

$cipher = new CipherWeaveManual();

// Encrypt data
$encryptedData = $cipher->encryptData($data, 'optional_custom_key');

// Decrypt data
$decryptedData = $cipher->decryptData($encryptedData, 'optional_custom_key');
```

### Advanced Usage

#### Disabling Encryption in Debug Mode

If you want to disable encryption while your application is in debug mode (e.g., during development), you can set the **disable_on_debug** configuration option to true in the `config/cipher.php` file:

```php
'disable_on_debug' => true,
```

This will bypass encryption and decryption processes when `APP_DEBUG` is set to true in your `.env` file.


## Contributing

Contributions are welcome! Please see the [CONTRIBUTING.md](CONTRIBUTING.md) file for more details on how to get involved.

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
