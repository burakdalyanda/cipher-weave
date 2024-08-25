<?php

namespace BurakDalyanda\CipherWeave;

/**
 * Class CipherWeaveProcessor
 *
 * Handles encryption and decryption of responses and files.
 */
class CipherWeaveManual
{
    private CipherWeave $cipherWeave;

    public function __construct()
    {
        $this->cipherWeave = new CipherWeave();
    }

    public function encryptData($data, $key = null): string
    {
        return $this->cipherWeave->encrypt($data, $key);
    }

    public function decryptData($data, $key = null)
    {
        return $this->cipherWeave->decrypt($data, $key);
    }
}
