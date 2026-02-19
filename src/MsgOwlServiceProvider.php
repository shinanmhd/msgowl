<?php

namespace Hadhiya\MsgOwl;

use Hadhiya\MsgOwl\Commands\MsgOwlCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MsgOwlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('msgowl')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_msgowl_table')
            ->hasCommand(MsgOwlCommand::class);
    }

    public function packageRegistered(): void
    {
        // Bind the main API wrapper as a singleton
        $this->app->singleton(MsgOwl::class, function () {
            return new MsgOwl(
                apiKey: config('msgowl.api_key') ?? '',
                senderId: config('msgowl.sender_id') ?? 'HADHIYA',
                otpKey: config('msgowl.otp_key'),
                otpSecret: config('msgowl.otp_secret')
            );
        });

        // Bind the Notification Channel
        $this->app->bind(MsgOwlChannel::class, function ($app) {
            return new MsgOwlChannel($app->make(MsgOwl::class));
        });
    }
}
