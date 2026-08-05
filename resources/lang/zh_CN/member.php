<?php

return [
    'status' => [
        'normal' => '正常',
        'disabled' => '禁用',
    ],

    'global_default' => [
        'navigation_group' => '会员管理',
    ],

    'notification' => [
        'account_disabled' => '您的账号已被禁用',
    ],

    'member_resource' => [
        'model_label' => '会员',
        'plural_model_label' => '会员管理',
        'navigation_label' => '会员管理',
        'tabs' => [
            'all' => '全部',
            'normal' => '正常',
            'disabled' => '已禁用',
        ],
        'table' => [
            'user' => '用户',
            'member_name' => '会员昵称',
            'team' => '团队',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
            'search_placeholder' => '搜索会员...',
        ],
        'filter' => [
            'status' => '状态',
        ],
        'action' => [
            'enable' => '启用',
            'disable' => '禁用',
            'enable_description' => '确定要启用该会员吗？',
            'disable_description' => '确定要禁用该会员吗？禁用后该会员将无法登录。',
            'bulk_enable' => '批量启用',
            'bulk_disable' => '批量禁用',
            'bulk_enable_description' => '确定要启用选中的会员吗？',
            'bulk_disable_description' => '确定要禁用选中的会员吗？禁用后该会员将无法登录。',
            'enable_success' => '会员已启用',
            'disable_success' => '会员已禁用',
        ],
        'form' => [
            'user_sync_tip' => '以下用户信息的修改将同步到关联的用户账户。',
        ],
    ],
];
