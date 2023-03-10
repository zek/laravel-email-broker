<?php

namespace Zek\EmailBroker\Contracts;

use Zek\EmailBroker\Contracts\CanChangeEmail as CanChangeEmailContract;

interface EmailBroker
{
    /**
     * Constant representing change email confirmation sent to current email.
     *
     * @var string
     */
    const CONFIRMATION_SENT = 'emails.confirmation';

    /**
     * Constant representing email changed successfully.
     *
     * @var string
     */
    const EMAIL_CHANGED = 'emails.changed';

    /**
     * Constant representing an invalid token.
     *
     * @var string
     */
    const INVALID_TOKEN = 'emails.token';

    /**
     * Change current email address
     * if email is verified, send a confirmation notification to the user to change current email
     *
     * @param  CanChangeEmailContract  $user
     * @param  string  $newEmail Change new mail address
     * @return string
     */
    public function changeEmail(CanChangeEmailContract $user, string $newEmail);

    /**
     * Send a confirmation notification to the user to change current email.
     *
     * @param  CanChangeEmail  $user Auth user
     * @param  string  $newEmail Change new mail address
     * @return string
     */
    public function sendChangeEmailConfirmation(CanChangeEmail $user, string $newEmail);

    /**
     * Change the user email address.
     *
     * @param  CanChangeEmail  $user
     * @param  string  $newEmail
     * @param  string  $token
     * @return mixed
     */
    public function confirmEmailChange(CanChangeEmail $user, string $newEmail, string $token);
}
