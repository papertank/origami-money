<?php 

namespace Origami\Money\Entities;

use Money\Currency;
use Money\Money;

trait HasMoney {

    public function asMoney($amount)
    {
        if ( $amount instanceof Money ) {
            return $amount;
        }

        if ( is_null($amount) ) {
            return null;
        }

        return new Money((int) $amount, $this->getCurrency());
    }

    protected function asMoneyInput($amount)
    {
        if ( is_null($amount) ) {
            return null;
        }

        return app('origami.money.decimalFormatter')->format($this->asMoney($amount));
    }

    protected function castAttributeAsMoney($value)
    {
        if ( $value instanceof Money ) {
            return $value->getAmount();
        }

        if ( is_null($value) ) {
            return null;
        }

        return $this->asMoney($value * 100)->getAmount();
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency instanceof Currency ? $currency->getName() : $currency;
        return $this;
    }

    public function getCurrency()
    {
        return new Currency($this->currency ?: config('money.default_currency'));
    }

}
