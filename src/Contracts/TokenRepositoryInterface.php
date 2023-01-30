<?php

namespace Zek\EmailBroker\Contracts;

interface TokenRepositoryInterface
{
    /**
     * Create a new token.
     *
     * @param CanChangeEmail $user
     * @param string $newEmail
     * @return string
     */
    public function create(CanChangeEmail $user, string $newEmail);

    /**
     * Determine if a token record exists and is valid.
     *
     * @param CanChangeEmail $user
     * @param string $newEmail
     * @param string $token
     * @return bool
     */
    public function exists(CanChangeEmail $user, string $newEmail, string $token): bool;

    /**
     * Delete a token record.
     *
     * @param CanChangeEmail $user
     * @return void
     */
    public function delete(CanChangeEmail $user);

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired();

}
