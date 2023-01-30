<?php

namespace Zek\EmailBroker;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ChangeEmailConfirmation extends Notification
{
    /**
     * The callback that should be used to create the email change URL.
     *
     * @var (\Closure(mixed, string): string)|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var (\Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage)|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @return void
     */
    public function __construct(public $token, public $email)
    {
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($this->confirmationUrl($notifiable), $this->email);
    }

    /**
     * Get the email change request notification mail message.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url, $email)
    {
        return (new MailMessage)
            ->subject(Lang::get('Confirm your new email address'))
            ->line(Lang::get('You are receiving this email because you requested to change your email address.'))
            ->line(Lang::get('New email address: :email', ['email' => $email]))
            ->action(Lang::get('Confirm new email address'), $url)
            ->line(Lang::get('This email change request will expire in :count minutes.', ['count' => config('auth.emails.'.config('auth.defaults.emails').'.expire')]))
            ->line(Lang::get('If you did not change your email address, contact us.'));
    }

    /**
     * Get the confirmation URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function confirmationUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }

        return url(route('email.confirm', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));
    }

    /**
     * Set a callback that should be used when creating the confirm email change button URL.
     *
     * @param  \Closure(mixed, string): string  $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
