<?php

namespace Zek\EmailBroker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zek\EmailBroker\Contracts\EmailBroker as EmailBrokerContract;

class EmailBrokerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-email-broker')
            ->hasMigration('create_email_changes_table');
    }

    /**
     * Register the email broker instance.
     *
     * @return void
     */
    public function registeringPackage()
    {
        $this->app->bind(EmailBrokerManager::class, function ($app) {
            return new EmailBrokerManager($app);
        });

        $this->app->bind(EmailBrokerContract::class, function ($app) {
            return $app->make(EmailBrokerManager::class)->broker();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [EmailBrokerManager::class, EmailBrokerContract::class];
    }
}
