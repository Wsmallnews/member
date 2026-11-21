<?php

namespace Wsmallnews\Member\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Member\Models\Member;
use Wsmallnews\Support\Features\Site\SiteContext;

/**
 * Enforces the member status rule of the current site.
 *
 * The identity itself is resolved by SiteContext through the `member` auth
 * resolver, so this middleware only signs out disabled accounts; the resolved
 * member stays cached in the context for the rest of the request.
 */
class EnsureMemberIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = app(SiteContext::class);

        // Panels and console have no site: no member to enforce there.
        if (! $context->has()) {
            return $next($request);
        }

        $member = $context->getAuthUser();

        if ($member instanceof Member && $member->status !== MemberStatus::Normal) {
            Auth::guard($context->getGuard())->logout();

            // The identity is gone from this request onward: drop the cached one.
            $context->authUser(null);

            Notification::make()
                ->title(__('sn-member::member.notification.account_disabled'))
                ->danger()->send();
        }

        return $next($request);
    }
}
