<?php

use Wsmallnews\Member\Models\Member;
use Wsmallnews\Support\Features\Site\SiteContext;

if (! function_exists('has_member')) {
    /**
     * 前端是否有当前会员（站点认证普通用户时为 false）
     */
    function has_member(): bool
    {
        return current_member() !== null;
    }
}

if (! function_exists('current_member')) {
    /**
     * 前端当前会员（站点 auth.user_type = member 时才有）
     */
    function current_member(): ?Member
    {
        $user = app(SiteContext::class)->getAuthUser();

        return $user instanceof Member ? $user : null;
    }
}
