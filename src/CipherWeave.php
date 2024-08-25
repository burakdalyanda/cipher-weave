<?php

namespace BurakDalyanda\CipherWeave;

use Illuminate\Encryption\Encrypter as LaravelEncrypter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

/**
 * Class CipherWeave
 *
 * Handles encryption and decryption of data using Laravel's Encrypter.
 */
class CipherWeave
{
    /**
     * @var LaravelEncrypter
     */
    protected LaravelEncrypter $encrypter;

    /**
     * @var bool
     */
    protected bool $disableOnDebug;

    /**
     * CipherWeave constructor.
     *
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     */
    public function __construct(?string $key = null)
    {
        $this->disableOnDebug = config('cipher.disable_on_debug');
        $this->encrypter = $this->createEncrypter($key);
    }

    /**
     * Creates an Encrypter instance.
     *
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return LaravelEncrypter
     */
    protected function createEncrypter(?string $key = null): LaravelEncrypter
    {
        $key = $key ?? config('cipher.key');

        if (Str::contains($key, 'base64:')) {
            $key = substr($key, 7);
        }

        $key = base64_decode($key);

        return new LaravelEncrypter($key, config('cipher.cipher'));
    }

    /**
     * Encrypts the given data.
     *
     * @param mixed $data Data to encrypt.
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return string Encrypted data.
     */
    public function encrypt(mixed $data, ?string $key = null): string
    {
        if ($this->shouldDisableEncryption()) {
            return $data; // Encryption disabled in debug mode
        }

        $encrypter = $this->createEncrypter($key);
        return $encrypter->encrypt($data);
    }

    /**
     * Decrypts the given encrypted data.
     *
     * @param string $encryptedData Data to decrypt.
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return mixed Decrypted data.
     */
    public function decrypt(string $encryptedData, ?string $key = null): mixed
    {
        if ($this->shouldDisableEncryption()) {
            return $encryptedData; // Encryption disabled in debug mode
        }

        $encrypter = $this->createEncrypter($key);
        return $encrypter->decrypt($encryptedData);
    }

    /**
     * Determines if encryption should be disabled based on the debug mode.
     *
     * @return bool True if encryption should be disabled, false otherwise.
     */
    protected function shouldDisableEncryption(): bool
    {
        return $this->disableOnDebug && App::isDebug();
    }
}
