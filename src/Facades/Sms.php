<?php

namespace Clickertech\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clickertech\Sms\Sms
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'clickertech-sms';
    }
}
