<?php namespace FI\Providers;

use FI\Modules\Currencies\Repositories\CurrencyRepository;
use FI\Modules\Settings\Repositories\SettingRepository;
use FI\Support\DateFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot(
        Request $request,
        CurrencyRepository $currencyRepository,
        SettingRepository $settingRepository
    )
    {
        $this->app->router->before(function ($request) use ($currencyRepository, $settingRepository)
        {
            // Set the application specific settings under fi. prefix (fi.settingName)
            if ($settingRepository->setAll())
            {
                // This one needs a little special attention
                $dateFormats = DateFormatter::formats();
                config(['fi.datepickerFormat' => $dateFormats[config('fi.dateFormat')]['datepicker']]);

                // Set the environment timezone to the application specific timezone, if available, otherwise UTC
                date_default_timezone_set((config('fi.timezone') ?: config('app.timezone')));

                $mailPassword = '';

                try
                {
                    $mailPassword = (config('fi.mailPassword')) ? Crypt::decrypt(config('fi.mailPassword')) : '';
                }
                catch (\Exception $e)
                {
                    if (config('fi.mailDriver') == 'smtp')
                    {
                        $this->app->session->flash('error', '<strong>' . trans('fi.error') . '</strong> - ' . trans('fi.mail_hash_error'));
                    }
                }

                // Override the framework mail configuration with the values provided by the application
                config(['mail.driver' => (config('fi.mailDriver')) ? config('fi.mailDriver') : 'smtp']);
                config(['mail.host' => config('fi.mailHost')]);
                config(['mail.port' => config('fi.mailPort')]);
                config(['mail.encryption' => config('fi.mailEncryption')]);
                config(['mail.username' => config('fi.mailUsername')]);
                config(['mail.password' => $mailPassword]);
                config(['mail.sendmail' => config('fi.mailSendmail')]);

                // Force the mailer to use these settings
                (new \Illuminate\Mail\MailServiceProvider(app()))->register();

                // Set the base currency to a config value
                config(['fi.currency' => $currencyRepository->findByCode(config('fi.baseCurrency'))]);
            }

            config(['fi.clientCenterRequest' => (($request->segment(1) == 'client_center') ? true : false)]);

            if (!config('fi.clientCenterRequest'))
            {
                $this->app->setLocale((config('fi.language')) ?: 'en');
            }
            elseif (config('fi.clientCenterRequest') and auth()->check() and auth()->user()->client_id)
            {
                $this->app->setLocale(auth()->user()->client->language);
            }

            config(['fi.mailConfigured' => (config('fi.mailDriver') ? true : false)]);
        });
    }

    public function register()
    {

    }
}
