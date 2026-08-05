<?php

return [
    'status' => [
        'normal' => 'Normal',
        'disabled' => 'Disabled',
    ],

    'global_default' => [
        'navigation_group' => 'Member Management',
    ],

    'notification' => [
        'account_disabled' => 'Your account has been disabled',
    ],

    'member_resource' => [
        'model_label' => 'Member',
        'plural_model_label' => 'Members',
        'navigation_label' => 'Members',
        'tabs' => [
            'all' => 'All',
            'normal' => 'Normal',
            'disabled' => 'Disabled',
        ],
        'table' => [
            'user' => 'User',
            'member_name' => 'Nickname',
            'team' => 'Team',
            'status' => 'Status',
            'created_at' => 'Registered At',
            'updated_at' => 'Updated At',
            'search_placeholder' => 'Search members...',
        ],
        'filter' => [
            'status' => 'Status',
        ],
        'action' => [
            'enable' => 'Enable',
            'disable' => 'Disable',
            'enable_description' => 'Are you sure you want to enable this member?',
            'disable_description' => 'Are you sure you want to disable this member? They will not be able to log in.',
            'bulk_enable' => 'Bulk Enable',
            'bulk_disable' => 'Bulk Disable',
            'bulk_enable_description' => 'Are you sure you want to enable the selected members?',
            'bulk_disable_description' => 'Are you sure you want to disable the selected members? They will not be able to log in.',
            'enable_success' => 'Member enabled',
            'disable_success' => 'Member disabled',
        ],
        'form' => [
            'user_sync_tip' => 'Changes to the following user information will be synced to the associated user account.',
        ],
    ],
];
