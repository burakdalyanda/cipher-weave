<?php

declare(strict_types=1);

namespace BurakDalyanda\CipherWeave\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string encrypt(mixed $data, ?string $key = null)
 * @method static mixed decrypt(string $encryptedData, ?string $key = null)
 *
 * @see \BurakDalyanda\CipherWeave\CipherWeave
 */
class CipherWeave extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \BurakDalyanda\CipherWeave\CipherWeave::class;
    }
}
