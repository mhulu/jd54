<?php

return [
    'menu' => [
        [
            'name' => '控制面板',
            'icon' => 'dashboard',
            'path' => '/dashboard/home',
            'permission' => 'general'
        ],
        [
            'name' => '个人中心',
            'icon' => 'vcard',
            'path' => '/me',
            'permission' => 'general',
            'children' => 
            [
                [
                    'name' => '我的资料',
                    'path' => '/me/profile'
                ],
                [
                    'name' => '通知提醒',
                    'path' => '/event',
                ]
            ]
        ],
        [
            'name' => '计划任务',
            'icon' => 'flag-o',
            'path' => '/task',
            'permission' => 'general'
        ],
        [
            'name' => '部门管理',
            'icon' => 'users',
            'path' => '/unit',
            'permission' => 'manage_units'
        ],
        [
            'name' => '微信管理',
            'icon' => 'weixin',
            'path' => '/weixin',
            'permission' => 'manage_wx'
        ],
        [
            'name' => '流动人口管理',
            'icon' => 'street-view',
            'path' => '/pops',
            'permission' => 'manage_pops'
        ],
        [
            'name' => '老年人口管理',
            'icon' => 'blind',
            'path' => '/aged',
            'permission' => 'manage_aged'
        ],
        [
            'name' => '内部人员管理',
            'icon' => 'user-plus',
            'path' => '/users',
            'permission' => 'manage_users'
        ],
        // [
        //     'name' => '站点设置',
        //     'icon' => 'cogs',
        //     'path' => '/site',
        //     'permission' => 'manage_system'
        // ],
    ],
    'permissions' => [
        ['name' => 'general', 'label' => '常规权限'],
        ['name' => 'view_posts', 'label' => '浏览文章'],
        ['name' => 'manage_system', 'label' => '管理后台系统'],
        ['name' => 'manage_users', 'label' => '管理用户'],
        ['name' => 'manage_posts', 'label' => '管理文章'],
        ['name' => 'manage_pops', 'label' => '管理流动人口'],
        ['name' => 'manage_aged', 'label' => '管理老年人口'],
        ['name' => 'manage_wx', 'label' => '管理微信服务号'],
        ['name' => 'manage_units', 'label' => '管理部门']
    ]
];
