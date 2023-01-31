<?php

namespace Origami\Money;

use Money\Currencies;
use Money\Currency;
use Origami\Money\Exceptions\CurrencyException;

class MoneyHelper
{
    /**
     * @var \Money\Currencies
     */
    protected $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    public function make($amount, $currency)
    {
        return $this->normalize($amount, $currency);
    }

    public function input($amount, $currency)
    {
        if ($amount instanceof Money) {
            return $amount;
        }

        $currency = $this->normalizeCurrency($currency);

        return new Money($this->adjustAmount($amount, $currency), $currency);
    }

    public function toDecimal(Money $money = null)
    {
        if (is_null($money)) {
            return null;
        }

        return app('origami-money.formatter')->formatDecimal($money);
    }

    public function toString(Money $money = null)
    {
        if (is_null($money)) {
            return null;
        }

        return app('origami-money.formatter')->format($money);
    }

    public function format(Money $money)
    {
        return $this->toString($money);
    }

    public function adjustAmount($amount, Currency $currency)
    {
        $subunit = $this->currencies->subunitFor($currency);

        if ($subunit == 0) {
            return $amount;
        }

        return round($amount * pow(10, $subunit), 0);
    }

    public function normalize($amount, $currency)
    {
        if (is_null($amount)) {
            return null;
        }

        if ($amount instanceof Money) {
            return $amount;
        }

        return new Money(ceil($amount), $currency);
    }

    protected function normalizeCurrency($currency)
    {
        if (is_null($currency)) {
            throw new CurrencyException('Missing currency');
        }

        if ($currency instanceof Currency) {
            return $currency;
        }

        return new Currency($currency);
    }
}
