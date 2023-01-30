<?php

namespace Zek\EmailBroker;

use Zek\EmailBroker\Contracts\CanChangeEmail as CanChangeEmailContract;
use Zek\EmailBroker\Contracts\EmailBroker as EmailBrokerContract;
use Zek\EmailBroker\Contracts\TokenRepositoryInterface;

class SimpleEmailBroker implements EmailBrokerContract
{
    /**
     * Token repository instance.
     *
     * @var TokenRepositoryInterface
     */
    protected $tokens;

    /**
     * Create a new email broker instance.
     *
     * @param  TokenRepositoryInterface  $tokens
     */
    public function __construct(TokenRepositoryInterface $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Send a confirmation notification to the user to change current email.
     *
     * @param  CanChangeEmailContract  $user
     * @param  string  $newEmail Change new mail address
     * @return string
     */
    public function sendChangeEmailConfirmation(CanChangeEmailContract $user, string $newEmail)
    {
        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the email change confirmation. Then we will insert a record in
        // the database so that we can verify the token within the actual email change.
        $token = $this->tokens->create($user, $newEmail);

        // Once we have the confirmation token, we are ready to send the message out to this
        // user with a link to change their email. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $user->sendChangeEmailNotification($token, $newEmail);

        return static::CONFIRMATION_SENT;
    }

    /**
     * Change the user email address.
     *
     * @param  CanChangeEmailContract  $user
     * @param  string  $newEmail
     * @param  string  $token
     * @return string.
     */
    public function confirmEmailChange(CanChangeEmailContract $user, string $newEmail, string $token)
    {
        if (! $this->tokens->exists($user, $newEmail, $token)) {
            return static::INVALID_TOKEN;
        }

        $user->changeEmail($newEmail);
        $this->tokens->delete($user);

        return static::EMAIL_CHANGED;
    }
}
