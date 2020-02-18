<?php

namespace Booni3\Linnworks;

use Illuminate\Support\Facades\Facade;

class LinnworksFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'linnworks';
    }
}