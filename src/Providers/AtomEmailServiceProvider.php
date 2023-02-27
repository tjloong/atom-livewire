<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Support\ServiceProvider;

class AtomEmailServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {        
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('atom.static_site')) return;

        try {
            $mailer = settings('mailer');

            if ($mailer === 'smtp') {
                config([
                    'mail.mailers.smtp.host' => settings('smtp_host'),
                    'mail.mailers.smtp.port' => settings('smtp_port'),
                    'mail.mailers.smtp.username' => settings('smtp_username'),
                    'mail.mailers.smtp.password' => settings('smtp_password'),
                    'mail.mailers.smtp.encryption' => settings('smtp_encryption'),
                ]);
            }
            else if ($mailer === 'mailgun') {
                config([
                    'services.mailgun.domain' => settings('mailgun_domain'),
                    'services.mailgun.secret' => settings('mailgun_secret'),
                ]);
            }
    
            config([
                'mail.default' => $mailer,
                'mail.from.address' => settings('notify_from'),
                'mail.from.name' => config('app.name'),
            ]);
        } catch (\Throwable $th) {
            logger('Unable to configure SMTP from site settings.');
            logger($th->getMessage());
        }
    }
}