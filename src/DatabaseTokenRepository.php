<?php

namespace Zek\EmailBroker;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;
use Zek\EmailBroker\Contracts\CanChangeEmail;
use Zek\EmailBroker\Contracts\TokenRepositoryInterface;

class DatabaseTokenRepository implements TokenRepositoryInterface
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected ConnectionInterface $connection;

    /**
     * The token database table.
     *
     * @var string
     */
    protected string $table;

    /**
     * The number of seconds a token should last.
     *
     * @var int
     */
    protected int $expires;

    /**
     * The length of generated token.
     *
     * @var int
     */
    protected int $length;

    /**
     * Create a new token repository instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  string  $table
     * @param  int  $expires
     */
    public function __construct(ConnectionInterface $connection, string $table, int $expires = 60, int $length = 6)
    {
        $this->table = $table;
        $this->expires = $expires * 60;
        $this->connection = $connection;
        $this->length = $length;
    }

    /**
     * Create a new token record.
     *
     * @param  CanChangeEmail  $user
     * @param  string  $newEmail
     * @return string
     */
    public function create(CanChangeEmail $user, string $newEmail)
    {
        $this->deleteExisting($user);

        $token = $this->generateToken($user, $newEmail);

        $this->getTable()->insert($this->getPayload($user, $newEmail, $token));

        return $token;
    }

    /**
     * Delete all existing change tokens from the database.
     *
     * @param  CanChangeEmail  $user
     * @return int
     */
    protected function deleteExisting(CanChangeEmail $user)
    {
        return $this->getTable()
            ->where('email', $user->getCurrentEmail())
            ->delete();
    }

    /**
     * Delete a token record by user.
     *
     * @param  CanChangeEmail  $user
     * @return void
     */
    public function delete(CanChangeEmail $user)
    {
        $this->deleteExisting($user);
    }

    /**
     * Begin a new database query against the table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getTable()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Build the record payload for the table.
     *
     * @param  CanChangeEmail  $user
     * @param  string  $newEmail
     * @param  string  $token
     * @return array
     */
    protected function getPayload(CanChangeEmail $user, $newEmail, $token)
    {
        return [
            'email' => $user->getCurrentEmail(),
            'new_email' => $newEmail,
            'token' => $token,
            'created_at' => new Carbon,
        ];
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  CanChangeEmail  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanChangeEmail $user, string $newEmail, string $token): bool
    {
        $record = (array) $this->getTable()
            ->where('email', $user->getCurrentEmail())
            ->first();

        return $record &&
            ! $this->tokenExpired($record['created_at']) &&
            $token === $record['token'] &&
            $newEmail === $record['new_email'];
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        $this->getTable()->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function generateToken(CanChangeEmail $user, $newEmail)
    {
        if (EmailBrokerManager::$generateTokenUsing) {
            return call_user_func(EmailBrokerManager::$generateTokenUsing, $user, $newEmail);
        }

        $token = '';
        for ($i = 0; $i < $this->length; $i++) {
            $token .= mt_rand(0, 9);
        }

        return $token;
    }
}
