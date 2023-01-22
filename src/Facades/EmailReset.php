<?php

namespace Zek\EmailReset\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zek\EmailReset\EmailReset
 */
class EmailReset extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Zek\EmailReset\EmailReset::class;
    }
}
