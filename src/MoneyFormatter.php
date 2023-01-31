<?php

namespace Origami\Money;

use Illuminate\Support\Manager;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use NumberFormatter;
use Origami\Money\Formatter\MoneyWithoutTrailingZeros;

class MoneyFormatter extends Manager
{
    public function format($amount)
    {
        return $this->driver()->format($this->castAmount($amount));
    }

    public function formatIntl($amount)
    {
        return $this->driver('intl')->format($this->castAmount($amount));
    }

    public function formatNeat($amount)
    {
        return $this->driver('neat')->format($this->castAmount($amount));
    }

    public function formatDecimal($amount)
    {
        return $this->driver('decimal')->format($this->castAmount($amount));
    }

    public function castAmount($amount)
    {
        if ($amount instanceof \Origami\Money\Money) {
            return $amount->asBase();
        }

        if ($amount instanceof \Money\Money) {
            return $amount;
        }

        return $amount;
    }

    public function createIntlDriver()
    {
        return new IntlMoneyFormatter(new NumberFormatter($this->container->getLocale(), NumberFormatter::CURRENCY), new ISOCurrencies);
    }

    public function createNeatDriver()
    {
        return new MoneyWithoutTrailingZeros(new NumberFormatter($this->container->getLocale(), NumberFormatter::CURRENCY), new ISOCurrencies);
    }

    public function createDecimalDriver()
    {
        return new DecimalMoneyFormatter(new ISOCurrencies);
    }

    public function getDefaultDriver()
    {
        return 'intl';
    }
}
