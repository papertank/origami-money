<?php

namespace Origami\Money\Facades;

use Illuminate\Support\Facades\Facade;

class Money extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'origami-money.helper';
    }
}
