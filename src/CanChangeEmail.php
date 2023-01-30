<?php

namespace Zek\EmailBroker;

trait CanChangeEmail
{

    /**
     * Send the email verification notification.
     *
     * @param string $token
     * @param string $email
     * @return void
     */
    public function sendChangeEmailNotification(string $token, string $email)
    {
        $this->notify(new ChangeEmailConfirmation($token, $email));
    }

    /**
     * Change current email address.
     *
     * @param string $email
     * @return void
     */
    public function changeEmail(string $email)
    {
        $this->email = $email;
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
