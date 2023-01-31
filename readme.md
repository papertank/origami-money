# Origami Push - Laravel Push Notifications

This package is a money helper for Laravel projects and a wrapper around [moneyphp/money](https://github.com/moneyphp/money).

## Installation

Install this package through Composer.

```
composer require origami/money
```

### Requirements

- This package is designed to work with Laravel >= 8.
- The `ext-intl` PHP extension is required.

### Configuration

First, publish the default configuration.

```bash
php artisan vendor:publish --tag="money-config"
```

This will add a new configuration file to: `config/push.php` which contains a `default_currency` value.

## Usage

TODO

## Versions
 - v2.* - Version 2 is a breaking change rewrite of the package to include better formatting and helper value object class.
 - v1.-* - Version 1 offered basic formatting and was compatible with Laravel 6-9.

## Author
[Papertank Limited](http://papertank.com)

## License
[View the license](http://github.com/papertank/origami-money/blob/master/LICENSE)
