<?php

return [
    'merchant_code' => env('DUITKU_MERCHANT_CODE'),
    'api_key'       => env('DUITKU_API_KEY'),
    'endpoint'      => env('DUITKU_ENDPOINT', 'https://api-sandbox.duitku.com/api/merchant/createInvoice'),
    'callback_url'  => env('DUITKU_CALLBACK_URL'),
    'return_url'    => env('DUITKU_RETURN_URL'),
];
