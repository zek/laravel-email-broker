<?php

namespace Zek\EmailBroker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zek\EmailBroker\EmailBrokerManager
 *
 * @method static \Zek\EmailBroker\Contracts\EmailBroker broker($broker = null)
 */
class EmailBroker extends Facade
{

    /**
     * Constant representing change email confirmation sent to current email.
     *
     * @var string
     */
    const CONFIRMATION_SENT = \Zek\EmailBroker\Contracts\EmailBroker::CONFIRMATION_SENT;

    /**
     * Constant representing email changed successfully.
     *
     * @var string
     */
    const EMAIL_CHANGED = \Zek\EmailBroker\Contracts\EmailBroker::EMAIL_CHANGED;

    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN =  \Zek\EmailBroker\Contracts\EmailBroker::INVALID_TOKEN;

    protected static function getFacadeAccessor()
    {
        return \Zek\EmailBroker\EmailBrokerManager::class;
    }
}
