<?php

namespace Zek\EmailReset;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zek\EmailReset\Commands\EmailResetCommand;

class EmailResetServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-email-reset')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-email-reset_table')
            ->hasCommand(EmailResetCommand::class);
    }
}
