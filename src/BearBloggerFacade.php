<?php

namespace AzurInspire\BearBlogger;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AzurInspire\BearBlogger\BearBlogger
 */
class BearBloggerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bear-blogger';
    }
}
