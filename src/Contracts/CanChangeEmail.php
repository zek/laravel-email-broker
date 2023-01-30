<?php

namespace Zek\EmailBroker\Contracts;

interface CanChangeEmail
{
    /**
     * Send change email confirmation.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function sendChangeEmailConfirmation(string $token, string $email);

    /**
     * Change current email address.
     *
     * @param  string  $email
     * @return void
     */
    public function changeEmail(string $email);

    /**
     * Returns current email address for change email notification.
     *
     * @return string
     */
    public function getCurrentEmail();
}
