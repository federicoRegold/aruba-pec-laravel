<?php

namespace Regoldidealista\ArubaPecMailer\Facades;

use Illuminate\Support\Facades\Facade;
use Regoldidealista\ArubaPecMailer\Contracts\ArubaPecMailerInterface;

class ArubaPec extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ArubaPecMailerInterface::class;
    }
}