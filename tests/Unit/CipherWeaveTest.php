<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave\Tests;

use BurakDalyanda\CipherWeave\CipherWeave;
use BurakDalyanda\CipherWeave\CipherWeaveServiceProvider;
use BurakDalyanda\CipherWeave\Exceptions\EncryptionException;
use BurakDalyanda\CipherWeave\Facades\CipherWeave as CipherWeaveFacade;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class CipherWeaveTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CipherWeaveServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'CipherWeave' => CipherWeaveFacade::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('cipherweave.key', 'base64:'.base64_encode(random_bytes(32)));
        Config::set('cipherweave.cipher', 'AES-256-CBC');
    }

    public function test_it_can_encrypt_and_decrypt_data()
    {
        $cipherWeave = new CipherWeave();

        $originalData = 'This is a secret message.';
        $encryptedData = $cipherWeave->encrypt($originalData);
        $decryptedData = $cipherWeave->decrypt($encryptedData);

        $this->assertNotEquals($originalData, $encryptedData);
        $this->assertEquals($originalData, $decryptedData);
    }

    public function test_it_can_encrypt_and_decrypt_arrays()
    {
        $cipherWeave = new CipherWeave();

        $originalData = ['key' => 'value', 'nested' => ['foo' => 'bar']];
        $encryptedData = $cipherWeave->encrypt($originalData);
        $decryptedData = $cipherWeave->decrypt($encryptedData);

        $this->assertEquals($originalData, $decryptedData);
    }

    public function test_it_can_use_custom_key()
    {
        $customKey = 'base64:'.base64_encode(random_bytes(32));
        $cipherWeave = new CipherWeave();

        $originalData = 'Another secret message.';
        $encryptedData = $cipherWeave->encrypt($originalData, $customKey);
        $decryptedData = $cipherWeave->decrypt($encryptedData, $customKey);

        $this->assertEquals($originalData, $decryptedData);
    }

    public function test_it_throws_exception_on_invalid_key()
    {
        $this->expectException(EncryptionException::class);

        $cipherWeave = new CipherWeave();
        $cipherWeave->encrypt('data', 'invalid-key');
    }

    public function test_it_can_be_disabled_in_debug_mode()
    {
        Config::set('cipherweave.disable_on_debug', true);
        $this->app['config']->set('app.debug', true);

        $cipherWeave = new CipherWeave();
        $data = 'Sensitive data';

        $encryptedData = $cipherWeave->encrypt($data);
        $this->assertEquals($data, $encryptedData);

        $decryptedData = $cipherWeave->decrypt($data);
        $this->assertEquals($data, $decryptedData);
    }

    public function test_facade_works()
    {
        $originalData = 'Facade test';
        $encrypted = CipherWeaveFacade::encrypt($originalData);
        $decrypted = CipherWeaveFacade::decrypt($encrypted);

        $this->assertEquals($originalData, $decrypted);
    }
}
