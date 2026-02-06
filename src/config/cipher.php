<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | The encryption key used for encrypting and decrypting data.
    | If not set here, it defaults to the APP_KEY defined in the .env file.
    |
    | You can set this value in your .env file by defining the CIPHER_KEY variable.
    |
    */

    'key' => env('CIPHER_KEY'),

    /**
     * The cipher algorithm used for encryption and decryption.
     * Supported: "AES-128-CBC", "AES-256-CBC"
     */
    'cipher' => env('CIPHER_ALGORITHM', 'AES-256-CBC'),

    /**
     * When set to true, encryption and decryption will be disabled if the application is in debug mode.
     */
    'disable_on_debug' => env('CIPHER_DISABLE_ON_DEBUG', false),
];
