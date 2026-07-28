<?php

namespace Wsmallnews\Member\Listeners;

use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Models\Member;

class AutoCreateMember
{
    public function handle(Login | Registered $event): void
    {
        $member = Member::findOrCreate($event->user);

        if ($member->status !== MemberStatus::Normal) {
            // 只有 Login 事件需要登出（Registered 创建出来的永远是 Normal）
            if ($event instanceof Login) {
                Auth::guard($event->guard)->logout();

                Notification::make()
                    ->title('您的账号已被禁用')
                    ->danger()->send();
            }
        }
    }
}
