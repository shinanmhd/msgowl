<?php

namespace Hadhiya\MsgOwl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hadhiya\MsgOwl\MsgOwl
 */
class MsgOwl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hadhiya\MsgOwl\MsgOwl::class;
    }
}
