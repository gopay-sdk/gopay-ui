<?php

namespace Gopay\GopayUi\Facades;

use Illuminate\Support\Facades\Facade;

class GopayUI extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gopayui';
    }
}
