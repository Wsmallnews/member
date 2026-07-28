<?php

namespace Wsmallnews\Member\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wsmallnews\Member\Models\Member;

class ResolveMember
{
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $user = $request->user($guard);

        if ($user) {
            $member = Member::findOrCreate($user);

            // livewire/update 请求时 request() 和 $request 不是同一个实例,这里统一用 request() (应该是和持久化 livewire 中间件有关系 https://livewire.laravel.com/docs/3.x/security#middleware)
            request()->attributes->set('has_sn_member', true);
            request()->attributes->set('current_sn_member', $member);
        }

        return $next($request);
    }
}
