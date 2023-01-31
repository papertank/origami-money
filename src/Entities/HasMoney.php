<?php

namespace Origami\Money\Entities;

use Origami\Money\Money;

trait HasMoney
{
    public function asMoney($amount)
    {
        if ($amount instanceof Money) {
            return $amount;
        }

        if (is_null($amount)) {
            return null;
        }

        return new Money(ceil($amount), $this->getCurrencyCode());
    }

    protected function zeroMoney()
    {
        return new Money(0, $this->getCurrencyCode());
    }

    public function setCurrencyCode($currency)
    {
        $this->currency = $currency instanceof \Money\Currency ? $currency->getCode() : $currency;

        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currency ?: config('money.default_currency');
    }

    /**
     * @alias getCurrencyCode
     */
    public function getCurrency()
    {
        return $this->getCurrencyCode();
    }
}
