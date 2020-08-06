<?php

namespace Azurinspire\BearBlogger;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Azurinspire\BearBlogger\BearBlogger
 */
class BearBloggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bear-blogger';
    }
}
