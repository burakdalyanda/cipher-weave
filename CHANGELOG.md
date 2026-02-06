# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-02-06

### Added
- Full support for Laravel 10, 11, and 12.
- Support for PHP 8.2 and 8.3.
- `BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface` for better Dependency Injection.
- `BurakDalyanda\CipherWeave\Facades\CipherWeave` Facade for easier access.
- `php artisan cipherweave:key` command to generate and set encryption keys.
- Custom Exception classes: `CipherWeaveException`, `EncryptionException`, `DecryptionException`.
- Added `strict_types=1` to all files.

### Changed
- **Breaking Change**: Updated minimum PHP version to 8.2.
- **Breaking Change**: Updated minimum Laravel version to 10.0.
- **Breaking Change**: Renamed config file internal keys (prefix with `cipherweave.`).
- Refactored `CipherWeave` class to use modern PHP 8.2 features (readonly properties).
- Modernized Middleware to use Constructor Injection and improved request/response handling.
- Updated Test suite to PHPUnit 11 and improved coverage.
- Updated `README.md` with Laravel 11/12 installation instructions.

### Removed
- Support for PHP < 8.2.
- Support for Laravel < 10.0.
- Removed unused `initial_vector` from configuration.
