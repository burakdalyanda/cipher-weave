<?php

namespace BurakDalyanda\CipherWeave\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use BurakDalyanda\CipherWeave\CipherWeave;

/**
 * Class EncryptRequestResponse
 *
 * Middleware for encrypting and decrypting requests and responses.
 */
class EncryptRequestResponse
{
    /**
     * @var CipherWeave
     */
    protected CipherWeave $cipherWeave;

    /**
     * EncryptRequestResponse constructor.
     *
     * @param CipherWeave $cipherWeave Instance of the CipherWeave class.
     */
    public function __construct(CipherWeave $cipherWeave)
    {
        $this->cipherWeave = $cipherWeave;
    }

    /**
     * Handles the request and response encryption/decryption.
     *
     * @param Request $request The incoming request.
     * @param Closure $next The next middleware or request handler.
     * @param string|null $key Optional encryption key. If not provided, the key from config is used.
     * @return JsonResponse The response after processing.
     */
    public function handle(Request $request, Closure $next, ?string $key = null)
    {
        $cipherWeave = new CipherWeave($key);

        if ($request->header('X-REQUEST-ENCRYPTED')) {
            $this->modifyRequest($request, $cipherWeave);
        }

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $this->modifyResponse($request, $response, $cipherWeave);
        }

        return $response;
    }

    /**
     * Modifies the request by decrypting its content if the request is encrypted.
     *
     * @param Request $request The incoming request.
     * @param CipherWeave $cipherWeave Instance of the CipherWeave class.
     * @return void
     */
    protected function modifyRequest(Request $request, CipherWeave $cipherWeave)
    {
        $decrypted = $cipherWeave->decrypt($request->getContent());
        $request->replace(json_decode($decrypted, true));
    }

    /**
     * Modifies the response by encrypting its content if the response should be encrypted.
     *
     * @param Request $request The incoming request.
     * @param JsonResponse $response The response to be sent.
     * @param CipherWeave $cipherWeave Instance of the CipherWeave class.
     * @return void
     */
    protected function modifyResponse(Request $request, JsonResponse $response, CipherWeave $cipherWeave)
    {
        $payload = $cipherWeave->encrypt($response->getContent());
        $response->setContent($payload);
        $response->header('X-RESPONSE-ENCRYPTED', '1');
    }
}
