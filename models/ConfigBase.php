<?php
namespace app\models;
/*
 * 配置的公共类
 */
class ConfigBase
{
    public static $vip = array(
        1 => '试用版',
        2 => '普通版',
        3 => '高级版',
    );

    public static function getVip($id){
        return isset(self::$vip[$id])? self::$vip[$id]:'出错';
    }

    public static $wxStatus = array(
        1 => '未开通',
        2 => '运行中',
        3 => '已停止',
        4 => '已到期',
    );

    public static function getWxStatus($id){
        return isset(self::$wxStatus[$id])? self::$wxStatus[$id]:'出错';
    }
}