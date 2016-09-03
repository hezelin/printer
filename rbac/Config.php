<?php
namespace app\rbac;

class Config {

    public static $roleName = [
        'front' => '前台',
        'manager' => '总经理',
        'boss' => '老板',
    ];

    public static function getRoleName($key)
    {
        return isset(self::$roleName[$key])? self::$roleName[$key]:'未知';
    }

}