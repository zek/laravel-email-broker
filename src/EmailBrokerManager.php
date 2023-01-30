<?php

namespace Zek\EmailBroker;

use InvalidArgumentException;
use Zek\EmailBroker\Contracts\EmailBroker as EmailBrokerBrokerContract;
use Zek\EmailBroker\Contracts\EmailBrokerFactory;
use Zek\EmailBroker\Contracts\TokenRepositoryInterface;

class EmailBrokerManager implements EmailBrokerFactory
{
    /**
     * The callback that should be used when creating the confirmation token.
     *
     * @var (\Closure(mixed, string): string)|null
     */
    public static $generateTokenUsing;

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $brokers = [];

    /**
     * Create a new PasswordBroker manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Set a callback that should be used when creating the confirmation token.
     *
     * @param \Closure(mixed): string $callback
     * @return void
     */
    public static function generateTokenUsing($callback)
    {
        static::$generateTokenUsing = $callback;
    }

    /**
     * Set the default password broker name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['auth.defaults.email'] = $name;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->broker()->{$method}(...$parameters);
    }

    /**
     * Attempt to get the broker from the local cache.
     *
     * @param  string  $name
     * @return EmailBrokerBrokerContract
     */
    public function broker($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->brokers[$name] ?? ($this->brokers[$name] = $this->resolve($name));
    }

    /**
     * Get the default password broker name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['auth.defaults.email'];
    }

    /**
     * Resolve the given broker.
     *
     * @param  string  $name
     * @return EmailBrokerBrokerContract
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Email broker [{$name}] is not defined.");
        }

        return new SimpleEmailBroker(
            $this->createTokenRepository($config)
        );
    }

    /**
     * Get the password broker configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["auth.email.{$name}"];
    }

    /**
     * Create a token repository instance based on the given configuration.
     *
     * @param  array  $config
     * @return TokenRepositoryInterface
     */
    protected function createTokenRepository(array $config)
    {
        $connection = $config['connection'] ?? null;

        return new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $config['table'],
            $config['expire'],
            $config['length'],
        );
    }
}
