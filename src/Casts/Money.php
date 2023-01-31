<?php

namespace Origami\Money\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;

class Money implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \Origami\Money\Money|null
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value instanceof \Origami\Money\Money) {
            return $value;
        }

        if (is_null($value)) {
            return null;
        }

        return $this->asMoney($value, $this->getCurrency($model, $attributes));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  \Origami\Money\Money|int|null  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, $key, $value, $attributes)
    {
        if ($value instanceof \Origami\Money\Money) {
            return (string) $value->getAmount();
        }

        if (is_null($value)) {
            return null;
        }

        if ($value == Arr::get($attributes, $key)) {
            return (string) $value;
        }

        return (string) app('origami-money.helper')->input($value, $this->getCurrency($model, $attributes))->getAmount();
    }

    protected function getCurrency($model, $attributes)
    {
        if (method_exists($model, 'getCurrencyCode')) {
            return $model->getCurrencyCode() ?: config('money.default_currency');
        }

        return Arr::get($attributes, 'currency', config('money.default_currency'));
    }

    protected function asMoney($amount, $currency)
    {
        if ($amount instanceof \Origami\Money\Money) {
            return $amount;
        }

        if (is_null($amount)) {
            return null;
        }

        return new \Origami\Money\Money((string) $amount, $currency);
    }
}
