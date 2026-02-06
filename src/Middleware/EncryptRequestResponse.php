<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave\Middleware;

use BurakDalyanda\CipherWeave\Contracts\CipherWeaveInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EncryptRequestResponse
 *
 * Middleware for encrypting and decrypting requests and responses.
 */
class EncryptRequestResponse
{
    /**
     * EncryptRequestResponse constructor.
     *
     * @param CipherWeaveInterface $cipherWeave Instance of the CipherWeave class.
     */
    public function __construct(
        protected readonly CipherWeaveInterface $cipherWeave
    ) {
    }

    /**
     * Handles the request and response encryption/decryption.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $key Optional encryption key.
     * @return Response
     */
    public function handle(Request $request, Closure $next, ?string $key = null): Response
    {
        if ($request->header('X-REQUEST-ENCRYPTED')) {
            $this->decryptRequest($request, $key);
        }

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $this->encryptResponse($response, $key);
        }

        return $response;
    }

    /**
     * Decrypts the request content.
     *
     * @param Request $request
     * @param string|null $key
     * @return void
     */
    protected function decryptRequest(Request $request, ?string $key = null): void
    {
        $decrypted = $this->cipherWeave->decrypt($request->getContent(), $key);

        if (is_string($decrypted)) {
            $data = json_decode($decrypted, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->replace($data);
            }
        }
    }

    /**
     * Encrypts the response content.
     *
     * @param JsonResponse $response
     * @param string|null $key
     * @return void
     */
    protected function encryptResponse(JsonResponse $response, ?string $key = null): void
    {
        $content = $response->getContent();

        if ($content !== false) {
            $encrypted = $this->cipherWeave->encrypt($content, $key);
            $response->setContent($encrypted);
            $response->headers->set('X-RESPONSE-ENCRYPTED', '1');
        }
    }
}
