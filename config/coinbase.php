<?php

return [
    // 'apiKey' => env('COINBASE_API_KEY'),
    'apiKey' => 'Y8KPw7gKc02RNKpr',
    'apiVersion' => '2023-01-19',
    'webhookSecret' => env('COINBASE_WEBHOOK_SECRET'),
    'webhookJobs' => [
        
    ],
    'webhookModel' => Shakurov\Coinbase\Models\CoinbaseWebhookCall::class,
];
