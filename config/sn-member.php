<?php

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

    /*
    |--------------------------------------------------------------------------
    | Auto-create Member
    |--------------------------------------------------------------------------
    |
    | When enabled, a Member record will be automatically created when a user
    | first visits a tenant they haven't been associated with yet.
    |
    */
    'auto_create' => true,

];
