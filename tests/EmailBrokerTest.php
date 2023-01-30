<?php

namespace Zek\EmailBroker\Tests;

use Illuminate\Contracts\Auth\UserProvider;
use Mockery as m;
use Zek\EmailBroker\Contracts\CanChangeEmail;
use Zek\EmailBroker\Contracts\EmailBroker as EmailBrokerContract;
use Zek\EmailBroker\Contracts\TokenRepositoryInterface;
use Zek\EmailBroker\SimpleEmailBroker;

class EmailBrokerTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testBrokerCreatesTokenAndSendsNotification()
    {
        $broker = $this->getBroker($mocks = $this->getMocks());
        $mocks['tokens']->shouldReceive('create')->once()->with($mocks['user'], 'email')->andReturn('token');
        $mocks['user']->shouldReceive('sendChangeEmailNotification')->once()->with('token', 'email');

        $this->assertSame(EmailBrokerContract::CONFIRMATION_SENT, $broker->sendChangeEmailConfirmation($mocks['user'], 'email'));
    }

    public function testInvalidCodeReturnedWhenRecordDoesntExistInTable()
    {
        $broker = $this->getBroker($mocks = $this->getMocks());
        $mocks['tokens']->shouldReceive('exists')->once()->with($mocks['user'], 'email', 'token')->andReturn(false);

        $this->assertSame(EmailBrokerContract::INVALID_TOKEN, $broker->confirmEmailChange($mocks['user'], 'email', 'token'));
    }

    public function testResetRemovesRecordOnTableAndChangesEmail()
    {
        $broker = $this->getBroker($mocks = $this->getMocks());

        $mocks['tokens']->shouldReceive('delete')->once()->with($mocks['user']);
        $mocks['tokens']->shouldReceive('exists')->once()->with($mocks['user'], 'email', 'token')->andReturn(true);
        $mocks['user']->shouldReceive('changeEmail')->once()->with('email');

        $this->assertSame(EmailBrokerContract::EMAIL_CHANGED, $broker->confirmEmailChange($mocks['user'], 'email', 'token'));
    }

    protected function getBroker($mocks)
    {
        return new SimpleEmailBroker($mocks['tokens'], $mocks['users']);
    }

    protected function getMocks()
    {
        return [
            'tokens' => m::mock(TokenRepositoryInterface::class),
            'users' => m::mock(UserProvider::class),
            'user' => m::mock(CanChangeEmail::class),
        ];
    }
}
