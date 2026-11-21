<?php

use Wsmallnews\Member\Filament\Resources\Members\MemberResource;
use Wsmallnews\Member\Models;

return [

    /*
    |--------------------------------------------------------------------------
    | Member Model
    |--------------------------------------------------------------------------
    |
    | The model class to use for members. You can swap this for your own
    | implementation as long as it extends the base Member model.
    |
    */
    'models' => [
        'member' => Models\Member::class,
    ],

    /**
     * Panel register
     *
     * 本包组件不消费 scope：引入无需声明 scopeable。
     *
     * global_default 共享默认（非 FQCN 的 string key）会合并到所有条目：
     *   - navigation_group: 所有页面/资源的默认导航组
     *
     * 资源清单（默认解开即注册；内存注入 resources([...]) 对同类覆盖）。
     */

    /**
     * File base directory (only used by filament default upload component (Forms\Components\FileUpload))
     */
    'file_directory' => 'sn/member/',
];
