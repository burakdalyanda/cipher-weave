<?php

namespace BurakDalyanda\CipherWeave\Tests;

use BurakDalyanda\CipherWeave\CipherWeave;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Tests\TestCase;

class CipherWeaveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set default configuration for testing
        config(['cipherweave.key' => 'base64:'.base64_encode('test-key')]);
        config(['cipherweave.cipher' => 'AES-128-CBC']);
    }

    public function testEncryptionAndDecryption()
    {
        $cipherWeave = new CipherWeave();

        $originalData = 'This is a secret message.';
        $encryptedData = $cipherWeave->encrypt($originalData);
        $decryptedData = $cipherWeave->decrypt($encryptedData);

        $this->assertNotEquals($originalData, $encryptedData);
        $this->assertEquals($originalData, $decryptedData);
    }

    public function testCustomKeyEncryptionAndDecryption()
    {
        $customKey = 'base64:'.base64_encode('custom-key');
        $cipherWeave = new CipherWeave($customKey);

        $originalData = 'Another secret message.';
        $encryptedData = $cipherWeave->encrypt($originalData);
        $decryptedData = $cipherWeave->decrypt($encryptedData);

        $this->assertNotEquals($originalData, $encryptedData);
        $this->assertEquals($originalData, $decryptedData);
    }

    public function testDisableEncryptionInDebugMode()
    {
        config(['cipherweave.disable_on_debug' => true]);
        app()->make(\Illuminate\Contracts\Console\Kernel::class)->call('config:cache'); // Clear cached config

        $cipherWeave = new CipherWeave();
        $data = 'Sensitive data';

        $encryptedData = $cipherWeave->encrypt($data);
        $decryptedData = $cipherWeave->decrypt($encryptedData);

        $this->assertEquals($data, $encryptedData);
        $this->assertEquals($data, $decryptedData);
    }
}
