<?php

namespace Ryuske\WepayLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class WepayLaravel extends Facade {

    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'wepay-laravel';
    }
}