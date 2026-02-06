<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave;

use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;
use BurakDalyanda\CipherWeave\Exceptions\DecryptionException;
use BurakDalyanda\CipherWeave\Exceptions\EncryptionException;
use Exception;
use Illuminate\Encryption\Encrypter as LaravelEncrypter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * Class CipherWeave
 *
 * Handles encryption and decryption of data using Laravel's Encrypter.
 */
class CipherWeave implements CipherWeaveInterface
{
    /**
     * @var bool
     */
    protected readonly bool $disableOnDebug;

    /**
     * CipherWeave constructor.
     */
    public function __construct()
    {
        $this->disableOnDebug = (bool) Config::get('cipherweave.disable_on_debug', false);
    }

    /**
     * Creates an Encrypter instance.
     *
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return LaravelEncrypter
     */
    protected function createEncrypter(?string $key = null): LaravelEncrypter
    {
        $key = $key ?? Config::get('cipherweave.key');
        $cipher = Config::get('cipherweave.cipher', 'AES-128-CBC');

        if (Str::contains((string) $key, 'base64:')) {
            $key = substr((string) $key, 7);
        }

        $decodedKey = base64_decode((string) $key, true);

        if ($decodedKey === false) {
             throw new EncryptionException("Invalid encryption key provided.");
        }

        return new LaravelEncrypter($decodedKey, (string) $cipher);
    }

    /**
     * Encrypts the given data.
     *
     * @param mixed $data Data to encrypt.
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return string Encrypted data.
     * @throws EncryptionException
     */
    public function encrypt(mixed $data, ?string $key = null): string
    {
        if ($this->shouldDisableEncryption()) {
            return is_string($data) ? $data : json_encode($data);
        }

        try {
            $encrypter = $this->createEncrypter($key);
            return $encrypter->encrypt($data);
        } catch (Exception $e) {
            throw new EncryptionException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Decrypts the given encrypted data.
     *
     * @param string $encryptedData Data to decrypt.
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return mixed Decrypted data.
     * @throws DecryptionException
     */
    public function decrypt(string $encryptedData, ?string $key = null): mixed
    {
        if ($this->shouldDisableEncryption()) {
            return $encryptedData;
        }

        try {
            $encrypter = $this->createEncrypter($key);
            return $encrypter->decrypt($encryptedData);
        } catch (Exception $e) {
            throw new DecryptionException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Determines if encryption should be disabled based on the debug mode.
     *
     * @return bool True if encryption should be disabled, false otherwise.
     */
    protected function shouldDisableEncryption(): bool
    {
        return $this->disableOnDebug && (bool) Config::get('app.debug');
    }
}
