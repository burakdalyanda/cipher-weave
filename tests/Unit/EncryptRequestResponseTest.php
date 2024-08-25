<?php

namespace BurakDalyanda\CipherWeave\Tests;

use BurakDalyanda\CipherWeave\Middleware\EncryptRequestResponse;
use BurakDalyanda\CipherWeave\CipherWeave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EncryptRequestResponseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set up test middleware
        $this->middleware = new EncryptRequestResponse(new CipherWeave());
    }

    public function testEncryptResponse()
    {
        $response = new JsonResponse(['message' => 'This is a response.']);

        $request = Request::create('/test', 'GET', [], [], [], ['HTTP_X_REQUEST_ENCRYPTED' => false]);

        $encryptedResponse = $this->middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertStringStartsWith('base64:', $encryptedResponse->getContent());
        $this->assertTrue($encryptedResponse->headers->has('X-RESPONSE-ENCRYPTED'));
    }

    public function testDecryptRequest()
    {
        $data = ['key' => 'value'];
        $cipherWeave = new CipherWeave();
        $encryptedData = $cipherWeave->encrypt(json_encode($data));

        $request = Request::create('/test', 'POST', [], [], [], [], $encryptedData);
        $request->headers->set('X-REQUEST-ENCRYPTED', '1');

        $decryptedRequest = $this->middleware->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals($data, $decryptedRequest->all());
    }
}
