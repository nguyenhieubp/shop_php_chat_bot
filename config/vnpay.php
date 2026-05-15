<?php

return [
    'vnp_TmnCode' => env('VNP_TMN_CODE', 'ABC12345'),
    'vnp_HashSecret' => env('VNP_HASH_SECRET', 'XYZ98765'),
    'vnp_Url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_Returnurl' => env('VNP_RETURN_URL', 'http://127.0.0.1:8000/vnpay-return'),
];
