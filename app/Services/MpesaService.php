<?php

namespace App\Services;

class MpesaService
{
    public function stkPush(array $data)
    {
        return [
            'status' => 'sent',
            'checkout_request_id' => 'ws_CO_123456'
        ];
    }
}
