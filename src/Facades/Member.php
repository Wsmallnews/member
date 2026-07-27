<?php

namespace Wsmallnews\Member\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Wsmallnews\Member\Member
 */
class Member extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Wsmallnews\Member\Member::class;
    }
}
