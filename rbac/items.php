<?php
/*
 * type == 2 为 权限
 * type == 1 为 角色
 *
 * 1、创建角色（老板 boss、总经理 manager 、前台 front）
 * 2、创建权限(  一般权限 general、修改功能 update、删除功能 delete、敏感数据 safety、超级权限 super)
 */
return [
    'general' =>[
        'type' => 2,
        'description' => '一般权限',
    ],
    'update' => [
        'type' => 2,
        'description' => '修改权限',
    ],
    'delete' => [
        'type' => 2,
        'description' => '修改权限',
    ],
    'safety' => [
        'type' => 2,
        'description' => '敏感权限',
    ],
    'super' => [
        'type' => 2,
        'description' => '超级权限',
    ],


    'front' => [
        'type' => 1,
        'description' => '前台角色',
        'children' => [
            'general',
        ]
    ],
    'manager' => [
        'type' => 1,
        'description' => '总经理角色',
        'children' => [
            'front',
            'update',
            'delete',
            'safety'
        ]
    ],
    'boss' =>[
        'type' => 1,
        'description' => '老板角色',
        'children' => [
            'manager',
            'super'
        ]
    ],
];
