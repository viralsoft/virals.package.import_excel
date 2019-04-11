<?php

namespace ViralsBackpack\BackPackExcel\Facades;

use Illuminate\Support\Facades\Facade;

class BackPackExcel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'backpackexcel';
    }
}
