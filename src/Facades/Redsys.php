<?php

namespace Ssheduardo\Redsys\Facades;

use Illuminate\Support\Facades\Facade;

class Redsys extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'tpv'; }

}