<?php

namespace Wsmallnews\Member\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Models\Member;

class ResolveMember
{
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $user = $request->user($guard);

        if ($user) {
            $member = Member::findOrCreate($user);

            if ($member->status === MemberStatus::Normal) {
                // livewire/update 请求时 request() 和 $request 不是同一个实例,这里统一用 request() (应该是和持久化 livewire 中间件有关系 https://livewire.laravel.com/docs/3.x/security#middleware)
                request()->attributes->set('has_sn_member', true);
                request()->attributes->set('current_sn_member', $member);
            } else {
                // 会员已被禁用，登出用户（处理管理员中途禁用的场景）
                Auth::guard($guard)->logout();

                Notification::make()
                    ->title('您的账号已被禁用')
                    ->danger()->send();
            }
        }

        return $next($request);
    }
}
