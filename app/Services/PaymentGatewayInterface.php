<?php

namespace App\Services;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function createInvoice(Order $order): array;

    public function handleCallback(array $payload): void;
}
