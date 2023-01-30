<?php

namespace Zek\EmailBroker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zek\EmailBroker\EmailBrokerManager
 */
class EmailBroker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Zek\EmailBroker\EmailBrokerManager::class;
    }
}
