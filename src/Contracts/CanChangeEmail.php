<?php

namespace Zek\EmailBroker\Contracts;

interface CanChangeEmail
{
    /**
     * Send the email verification notification.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function sendChangeEmailNotification(string $token, string $email);

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
