<?php

use Wsmallnews\Member\Models\Member;

if (! function_exists('has_member')) {
    /**
     * 前端是否有租户
     */
    function has_member(): bool
    {
        return request()->attributes->get('has_sn_member', false);
    }
}

if (! function_exists('current_member')) {
    /**
     * 前端当前会员
     */
    function current_member(): ?Member
    {
        return request()->attributes->get('current_sn_member', null);
    }
}
