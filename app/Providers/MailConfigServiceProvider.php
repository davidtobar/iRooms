<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (\Schema::hasTable('mail')) {
            $mail = DB::table('mail')->first();
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => $mail->mail_host,
                    'port'       => $mail->mail_port,
                    'from'       => array('address' => $mail->smtp_email, 'name' => $mail->from_name),
                    'encryption' => 'tls',
                    'username'   => $mail->smtp_email,
                    'password'   => $mail->password,
                    'sendmail'   => '/usr/sbin/sendmail -bs'
                );
                Config::set('mail', $config);
            }
        }
    }
}