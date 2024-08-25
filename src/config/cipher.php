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

    'key' => env('CIPHER_KEY', env('APP_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Encryption Cipher
    |--------------------------------------------------------------------------
    |
    | The cipher algorithm used for encryption and decryption.
    | This should match the encryption method used by Laravel's Encrypter.
    |
    | Supported: "AES-128-CBC", "AES-256-CBC"
    |
    */

    'cipher' => env('CIPHER_ALGORITHM', 'AES-128-CBC'),

    /*
    |--------------------------------------------------------------------------
    | Initialization Vector
    |--------------------------------------------------------------------------
    |
    | The initialization vector used in the encryption process.
    | This should be a 16-byte base64 encoded string for AES-128-CBC.
    |
    */

    'initial_vector' => env('CIPHER_IV', '0123456789ABCDEF'),

    /*
    |--------------------------------------------------------------------------
    | Disable Encryption in Debug Mode
    |--------------------------------------------------------------------------
    |
    | When set to true, encryption and decryption will be disabled if the application is in debug mode.
    | This can be useful for testing purposes where you want to bypass encryption.
    |
    */

    'disable_on_debug' => false,

];
