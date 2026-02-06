<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave\Contracts;

interface CipherWeaveInterface
{
    /**
     * Encrypt the given data.
     *
     * @param mixed $data
     * @param string|null $key
     * @return string
     */
    public function encrypt(mixed $data, ?string $key = null): string;

    /**
     * Decrypt the given data.
     *
     * @param string $encryptedData
     * @param string|null $key
     * @return mixed
     */
    public function decrypt(string $encryptedData, ?string $key = null): mixed;
}
