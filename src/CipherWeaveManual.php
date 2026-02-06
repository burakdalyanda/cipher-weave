<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave;

use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;
use Illuminate\Support\Facades\App;

/**
 * Class CipherWeaveManual
 *
 * Facilitator for manual encryption and decryption.
 */
class CipherWeaveManual
{
    /**
     * @var CipherWeaveInterface
     */
    private readonly CipherWeaveInterface $cipherWeave;

    /**
     * CipherWeaveManual constructor.
     */
    public function __construct()
    {
        $this->cipherWeave = App::make(CipherWeaveInterface::class);
    }

    /**
     * Encrypt data manually.
     *
     * @param mixed $data
     * @param string|null $key
     * @return string
     */
    public function encryptData(mixed $data, ?string $key = null): string
    {
        return $this->cipherWeave->encrypt($data, $key);
    }

    /**
     * Decrypt data manually.
     *
     * @param string $data
     * @param string|null $key
     * @return mixed
     */
    public function decryptData(string $data, ?string $key = null): mixed
    {
        return $this->cipherWeave->decrypt($data, $key);
    }
}
