<?php

namespace Zek\EmailBroker;

use Illuminate\Contracts\Auth\MustVerifyEmail;

trait CanChangeEmail
{
    /**
     * Send change email confirmation.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function sendChangeEmailConfirmation(string $token, string $email)
    {
        $this->notify(new ChangeEmail($token, $email));
    }

    /**
     * Change current email address.
     *
     * @param  string  $email
     * @return void
     */
    public function changeEmail(string $email)
    {
        $this->email = $email;

        if ($this instanceof MustVerifyEmail) {
            $this->email_verified_at = null;
        }

        $this->save();
    }

    /**
     * Returns current email address for change email notification.
     *
     * @return string
     */
    public function getCurrentEmail()
    {
        return $this->email;
    }
}
