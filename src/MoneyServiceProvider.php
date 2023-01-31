<?php

namespace Origami\Money;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Money\Currencies\ISOCurrencies;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/money.php',
            'money'
        );

        $this->app->singleton(MoneyFormatter::class, function ($app) {
            return new MoneyFormatter($app);
        });
        $this->app->alias(MoneyFormatter::class, 'origami-money.formatter');

        $this->app->singleton(MoneyHelper::class, function ($app) {
            return new MoneyHelper(new ISOCurrencies);
        });
        $this->app->alias(MoneyHelper::class, 'origami-money.helper');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/money.php' => config_path('money.php'),
            ], 'money-config');
        }

        Blade::directive('money', function ($expression) {
            return "<?php echo app('origami-money.formatter')->format($expression); ?>";
        });

        Blade::directive('moneyNeat', function ($expression) {
            return "<?php echo app('origami-money.formatter')->formatNeat($expression); ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['origami-money.formatter', 'origami-money.helper'];
    }
}
