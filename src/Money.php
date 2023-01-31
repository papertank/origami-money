<?php

namespace Origami\Money;

use Illuminate\Contracts\Database\Eloquent\Castable;
use JsonSerializable;
use Origami\Money\Casts\Money as MoneyCast;
use Origami\Money\Exceptions\MoneyException;

class Money implements JsonSerializable, Castable
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;

    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    const ROUND_UP = \Money\Money::ROUND_UP;

    const ROUND_DOWN = \Money\Money::ROUND_DOWN;

    const ROUND_HALF_POSITIVE_INFINITY = \Money\Money::ROUND_HALF_POSITIVE_INFINITY;

    const ROUND_HALF_NEGATIVE_INFINITY = \Money\Money::ROUND_HALF_NEGATIVE_INFINITY;

    /**
     * @var \Money\Money
     */
    private $money;

    public function __construct($amount, $currency)
    {
        $this->money = new \Money\Money($this->normalizeAmount($amount), $this->normalizeCurrency($currency));
    }

    public static function castUsing(array $arguments)
    {
        return MoneyCast::class;
    }

    public static function make($amount, $currency)
    {
        return new static($amount, $currency);
    }

    public static function instance($money)
    {
        if ($money instanceof static) {
            return $money->copy();
        }

        if ($money instanceof \Money\Money) {
            return new static($money->getAmount(), $money->getCurrency());
        }

        $class = get_called_class();
        $type = gettype($money);

        throw new MoneyException(
            'Argument 1 passed to '.$class.'::'.__METHOD__.'() '.
            'must be an instance of \Money\Money or '.$class.', '.
            ($type === 'object' ? 'instance of '.get_class($money) : $type).' given.'
        );
    }

    public function copy()
    {
        return clone $this;
    }

    /**
     * @alias copy
     */
    public function clone()
    {
        return $this->copy();
    }

    public function asBase()
    {
        return $this->money;
    }

    public function isSameCurrency(Money $other)
    {
        return $this->money->isSameCurrency($other->asBase());
    }

    public function equals(Money $money)
    {
        return $this->money->equals($money->asBase());
    }

    public function compare(Money $other)
    {
        return $this->money->compare($other->asBase());
    }

    public function greaterThan(Money $money)
    {
        return $this->money->greaterThan($money->asBase());
    }

    public function gt(Money $money)
    {
        return $this->greaterThan($money);
    }

    public function greaterThanOrEqual(Money $money)
    {
        return $this->money->greaterThanOrEqual($money->asBase());
    }

    public function gte(Money $money)
    {
        return $this->greaterThanOrEqual($money);
    }

    public function lessThan(Money $money)
    {
        return $this->money->lessThan($money->asBase());
    }

    public function lt(Money $money)
    {
        return $this->lessThan($money);
    }

    public function lessThanOrEqual(Money $money)
    {
        return $this->money->lessThanOrEqual($money->asBase());
    }

    public function lte(Money $money)
    {
        return $this->lessThanOrEqual($money);
    }

    public function percentageOf(self $money, $overflow = true)
    {
        $percentage = (1 - $this->money->ratioOf($money->asBase()));

        if ($overflow) {
            return round($percentage * 100);
        }

        if ($percentage >= 1) {
            return '100';
        }

        if ($percentage >= 0.99) {
            return '99';
        }

        return round($percentage * 100);
    }

    public function getAmount()
    {
        return $this->money->getAmount();
    }

    public function getCurrency()
    {
        return $this->money->getCurrency();
    }

    public function getCurrencyCode()
    {
        return $this->money->getCurrency()->getCode();
    }

    public function add(Money ...$parts)
    {
        return static::instance($this->money->add(...array_map(function ($part) {
            return $part->asBase();
        }, $parts)));
    }

    public function subtract(Money ...$parts)
    {
        return static::instance($this->money->subtract(...array_map(function ($part) {
            return $part->asBase();
        }, $parts)));
    }

    public function multiply($multiplier, $roundingMode = self::ROUND_HALF_UP)
    {
        return static::instance($this->money->multiply($multiplier, $roundingMode));
    }

    public function divide($divisor, $roundingMode = self::ROUND_HALF_UP)
    {
        return static::instance($this->money->divide($divisor, $roundingMode));
    }

    public function mod(Money $divisor)
    {
        return static::instance($this->money->mod($divisor->asBase()));
    }

    public function ratioOf(Money $money)
    {
        return $this->money->ratioOf($money->asBase());
    }

    public function absolute()
    {
        return static::instance($this->money->absolute());
    }

    public function negative()
    {
        return static::instance($this->money->negative());
    }

    public function isZero()
    {
        return $this->money->isZero();
    }

    public function isPositive()
    {
        return $this->money->isPositive();
    }

    public function isNegative()
    {
        return $this->money->isNegative();
    }

    protected function normalizeAmount($amount)
    {
        if ($amount instanceof \Money\Money) {
            return $amount->getAmount();
        }

        return (string) $amount;
    }

    protected function normalizeCurrency($currency)
    {
        if ($currency instanceof \Money\Currency) {
            return $currency;
        }

        return new \Money\Currency($currency);
    }

    public function jsonSerialize()
    {
        return $this->money->jsonSerialize();
    }

    public function format()
    {
        return app('origami-money.formatter')->format($this->asBase());
    }

    public function formatNeat()
    {
        return app('origami-money.formatter')->formatNeat($this->asBase());
    }

    public function formatDecimal()
    {
        return app('origami-money.formatter')->formatDecimal($this->asBase());
    }

    /**
     * @alias toDecimal
     */
    public function toDecimal()
    {
        return $this->formatDecimal();
    }

    public function __toString()
    {
        return (string) $this->format();
    }
}
