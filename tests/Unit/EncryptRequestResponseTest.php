<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave\Tests;

use BurakDalyanda\CipherWeave\CipherWeaveServiceProvider;
use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;
use BurakDalyanda\CipherWeave\Middleware\EncryptRequestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class EncryptRequestResponseTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CipherWeaveServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('cipherweave.key', 'base64:'.base64_encode(random_bytes(32)));
        Config::set('cipherweave.cipher', 'AES-256-CBC');
    }

    public function test_it_decrypts_request_if_header_is_present()
    {
        $cipher = $this->app->make(CipherWeaveInterface::class);
        $data = ['foo' => 'bar'];
        $encrypted = $cipher->encrypt(json_encode($data));

        $request = Request::create('/test', 'POST', [], [], [], [
            'HTTP_X-REQUEST-ENCRYPTED' => '1',
            'CONTENT_TYPE' => 'application/json'
        ], $encrypted);

        $middleware = new EncryptRequestResponse($cipher);

        $middleware->handle($request, function ($req) use ($data) {
            $this->assertEquals($data, $req->all());
            return new JsonResponse(['status' => 'ok']);
        });
    }

    public function test_it_encrypts_json_response()
    {
        $cipher = $this->app->make(CipherWeaveInterface::class);
        $request = Request::create('/test', 'GET');

        $middleware = new EncryptRequestResponse($cipher);

        $response = $middleware->handle($request, function ($req) {
            return new JsonResponse(['message' => 'hello']);
        });

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->headers->has('X-RESPONSE-ENCRYPTED'));

        $decrypted = $cipher->decrypt($response->getContent());
        $this->assertEquals('{"message":"hello"}', $decrypted);
    }
}
