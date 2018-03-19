<?php

namespace Origami\Money;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;

class MoneyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/money.php', 'money'
        );

        $this->app->singleton('origami.money.intlFormatter', function($app) {
            return new IntlMoneyFormatter(new NumberFormatter($this->app->getLocale(), NumberFormatter::CURRENCY), new ISOCurrencies);
        });

        $this->app->singleton('origami.money.decimalFormatter', function($app) {
            return new DecimalMoneyFormatter(new ISOCurrencies);
        });
    }

    public function boot()
    {
        if ( $this->app->runningInConsole() ) {
            $this->publishConfig();
        }

        Blade::directive('money', function($expression) {
            return "<?php echo $expression ? app('origami.money.intlFormatter')->format($expression) : ''; ?>";
        });
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/money.php' => config_path('money.php'),
        ], 'money-config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['origami.money.intlFormatter', 'origami.money.decimalFormatter'];
    }
}
