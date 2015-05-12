<?php
namespace app\models;
/*
 * 配置的公共类
 */
class ConfigBase
{
    /*
     * 公众号 等级
     */
    public static $vip = array(
        1 => '试用版',
        2 => '普通版',
        3 => '高级版',
    );

    public static function getVip($id){
        return isset(self::$vip[$id])? self::$vip[$id]:'出错';
    }


    /*
     * 公众号 状态
     */
    public static $wxStatus = array(
        1 => '未开通',
        2 => '运行中',
        3 => '已停止',
        4 => '已到期',
    );

    public static function getWxStatus($id){
        return isset(self::$wxStatus[$id])? self::$wxStatus[$id]:'出错';
    }

    /*
     * 机器租借状态
     */
    public static $mxStatus = array(
        1 => '闲置中',
        2 => '已租借',
    );

    public static function getMxStatus($id){
        return isset(self::$mxStatus[$id])? self::$mxStatus[$id]:'出错';
    }

    /*
     * 租借申请
     */
    public static $rentStatus = [
        1 => '审核中',
        2 => '租借成功',
        3 => '资料错误'
    ];

    public static function getRentStatus($id){
        return isset(self::$rentStatus[$id])? self::$rentStatus[$id]:'出错';
    }

    /*
     * 故障类型列表
     */
    public static $faultStatus = [
        1 => '卡纸',
        2 => '坏晒鼓',
        3 => '其他'
    ];

    public static function getFaultStatus($id){
        return isset(self::$faultStatus[$id])? self::$faultStatus[$id]:'出错';
    }

    /*
     * 维修进度
     */
    public static $fixStatus = [
        1 => '申请中',
        2 => '任务分配中',
        3 => '维修员已接',
        4 => '维修员到达',
        5 => '故障已确认',
        6 => '申请配件中',
        7 => '配件派送中',
        8 => '维修中',
        9 => '维修完成',
        10 => '评价完成',
    ];

    public static function getFixStatus($id){
        return isset(self::$fixStatus[$id])? self::$fixStatus[$id]:'出错';
    }
}