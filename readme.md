# Origami Money for Laravel

This package is a money helper for Laravel projects and a wrapper around [moneyphp/money](https://github.com/moneyphp/money).

## Installation

Install this package through Composer.

```
composer require origami/money
```

## Requirements

- This package is designed to work with Laravel >= 8.
- The `ext-intl` PHP extension is required.

## Configuration

First, publish the default configuration.

```bash
php artisan vendor:publish --tag="money-config"
```

This will add a new configuration file to: `config/money.php` which contains a `default_currency` value.

## Usage

### Value Object

The `Origami\Money\Money` value object class is a helper that uses a `Money\Money` object (from [moneyphp/money](https://github.com/moneyphp/money)) under the hood. This allows us to add some helper methods (e.g. for formatting) and be more opinionated about our implementation. The class does not extend `Money\Money` since that is a `final class` - instead we use an internal attribute.

As with `Money\Money`, each `Origami\Money\Money` object requires an:

- `$amount`, expressed in the smallest units of $currency (eg cents)
- `$currency`, an ISO-4217 3 character code for currency.

For example:

```php
$money = new Origami\Money\Money(100, 'GBP') // £1
$money = new Origami\Money\Money(50000, 'USD') // $500
$money = Origami\Money\Money::make(1000000, 'USD') // $10,000
```

You can also pass a moneyphp object:

```php
$currency = new Money\Currency('GBP');
$base = new Money\Money(100, $currency);
$money = new Origami\Money\Money($base, $currency);
```

### Available Methods

#### instance
Create an instance from `Money\Money` object:

```php
Origami\Money\Money::instance(new Money\Money(500, new Money\Currency('GBP')));
```

#### copy
Create a copy of the instance

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$another = $money->copy();
$andAnother = $money->clone(); // Alias for above
```

#### asBase
Get the underlying `Money\Money` object

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$base = $money->asBase(); // Money\Money
```

#### isSameCurrency

```php
$gbp = new Origami\Money\Money(500, 'GBP'); // £5
$usd = new Origami\Money\Money(500, 'USD'); // $5
$money->isSameCurrency(); // false
```

#### equals

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = Origami\Money\Money::make(500, 'GBP'); // £5
$money->equals(); // true
```

#### greaterThan / gt

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(600, 'GBP'); // £6
$second->greaterThan($first); // true
$second->gt($first); // Alias for above
```

#### greaterThanOrEqual / gte

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(500, 'GBP'); // £5
$second->greaterThanOrEqual($first); // true
$second->gte($first); // Alias for above
```

#### lessThan / lt

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(600, 'GBP'); // £6
$second->lessThan($first); // false
$second->lt($first); // Alias for above
```

#### lessThanOrEqual / lte

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(500, 'GBP'); // £5
$second->lessThanOrEqual($first); // true
$second->lte($first); // Alias for above
```

#### isZero

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$zero = new Origami\Money\Money(0, 'GBP'); // £0
$first->isZero(); // false
$zero->isZero(); // true
```

#### isPositive

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$first->isPositive(); // true
```

#### isNegative

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$first->isNegative(); // false
```

#### percentageOf

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(1000, 'GBP'); // £10
$first->percentageOf($second); // 50
```

Set `$overflow` argument to `false` to not go over 100
```php
$first = new Origami\Money\Money(1000, 'GBP'); // £10
$second = new Origami\Money\Money(500, 'GBP'); // £5
$first->percentageOf($second, false); // 100
```

#### getAmount
Returns `integer`

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$amount = $money->getAmount(); // 500
```

#### getCurrency
Returns `Money\Money` currency value object

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$currency = $money->getCurrency(); // `Money\Currency('GBP')`
```

#### getCurrencyCode

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$currency = $money->getCurrencyCode(); // "GBP"
```

#### add

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(400, 'GBP'); // £4
$first->add($second); // `Origami\Money(900, 'GBP')`
```

#### subtract

```php
$first = new Origami\Money\Money(500, 'GBP'); // £5
$second = new Origami\Money\Money(400, 'GBP'); // £4
$first->add($second); // `Origami\Money(100, 'GBP')`
```

#### multiply

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$money->multiply(2); // `Origami\Money(1000, 'GBP')`
```

#### divide

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$money->divide(2); // `Origami\Money(250, 'GBP')`
```

#### mod
Modulus operation

```php
$money = new Origami\Money\Money(830, 'GBP'); // £8.30
$divisor = new Origami\Money\Money(300, 'GBP'); // £3.00
$money->add($divisor); // `Origami\Money(230, 'GBP')`
```

#### ratioOf
Provides the ratio of a Money object compared to another

```php
$three = new Origami\Money\Money(300, 'GBP'); // £3
$six = new Origami\Money\Money(600, 'GBP'); // £6
$three->ratioOf($six); // 0.5
```

#### absolute

```php
$money = new Origami\Money\Money(-500, 'GBP'); // -£5
$money->absolute(); // `Origami\Money(500, 'GBP')`
```

#### negative

```php
$money = new Origami\Money\Money(500, 'GBP'); // £5
$money->negative(); // `Origami\Money(-500, 'GBP')`
```

## Formatting

### String
Uses Intl formatter for currency symbol and separatator.
```php
$money = new Origami\Money\Money(500, 'GBP');
$money->format(); // Outputs: £5.00
(string) $money; // Outputs: £5.00 (uses `__toString()`)
app('origami-money.formatter')->format($money) // Outputs: £5.00
```

### String Neat (without Trailing Zeros)
Only drops trailing zeros were decimal is zero.
```php
$money = new Origami\Money\Money(500, 'GBP');
$money->formatNeat(); // Outputs: £5
app('origami-money.formatter')->formatNeat($money) // Outputs: £5
```

### Decimal
```php
$money = new Origami\Money\Money(500, 'GBP');
$money->formatDecimal(); // Outputs: 5.00
$money->toDecimal(); // Alias for above
app('origami-money.formatter')->formatDecimal($money) // Outputs: 5.00
```

## Blade Directives

You can use the following directives in your Blade views:

```php
@money($money)
// same as `$money->format()`
```

```php
@moneyNeat($money)
// same as `$money->formatNeat()`
```

## Eloquent Attribute Cast

You are most likely storing your money values in your Eloquent models. This package provides an `Origami\Money\Casts\Money` [custom Laravel cast](https://laravel.com/docs/10.x/eloquent-mutators#custom-casts) for you to use:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Origami\Money\Casts\Money;

class Invoice extends Model
{
    protected $fillable = ['description','total', 'currency'];

    protected $casts = ['total' => Money::class];
}
```

In the example above, you can set the `total` attributes using a Money object:

```php
$currency = 'USD';
$invoice = new Invoice(['currency' => $currency, 'total' => new Origami\Money\Money(70000, $currency)]);
```

In the example above, `$invoice->total` will be an instance of:

```
Origami\Money\Money {
  -money: Money\Money {
    -amount: "70000",
    -currency: Money\Currency {
      -code: "GBP",
    },
  },
}
```

You can also use a float, string or integer value when setting the casted attribute - for example from a controller.

```php
$input = [
    'currency' => 'GBP',
    'total' => '10.50',
];

$invoice = new Invoice($input);
```

**Important**: This will use the `Origami\Money\MoneyHelper@input` method behind the scenes (which ultimately uses `Origami\Money\MoneyHelper@adjustAmount`), transforming the amount to the smallest unit of currency after assuming it is given as dollars or pounds, for example.

Your model should have a `currency` attribute or a `getCurrencyCode` method with / which returns the ISO-4217 code. Otherwise, this package will default to the `default_currency` set in the config file.

The above therefore converts to:

```
Origami\Money\Money {
  -money: Money\Money {
    -amount: "1050",
    -currency: Money\Currency {
      -code: "GBP",
    },
  },
}
```

If your controllers or other methods already expect user input in cents rather than dollars, you should either not use the cast or instead pass an `Origami\Money\Money` object instead of the numerical amount (which would be tranformed).


## Versions
 - v2.* - Version 2 is a breaking change rewrite of the package to include better formatting and helper value object class.
 - v1.-* - Version 1 offered basic formatting and was compatible with Laravel 6-9.

## Author
- [David Rushton](https://github.com/davidrushton)
- [Papertank Limited](http://papertank.com)

## License
[View the license](http://github.com/papertank/origami-money/blob/master/LICENSE)
