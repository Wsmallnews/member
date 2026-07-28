<?php

namespace Wsmallnews\Member\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Wsmallnews\Member\Models\Member;

class AutoCreateMember
{
    public function handle(Login | Registered $event): void
    {
        Member::findOrCreate($event->user);
    }
}
