<?php
namespace app\models\config;
/*
 * 租赁方案配置类
 */
use yii\helpers\ArrayHelper;

class ConfigScheme
{
    /*
     * 品牌
     */
    public static $brand = [
        'dz' => '东芝',
        'fssl' => '富士施乐',
        'jc' => '京瓷',
        'jn' => '佳能',
        'knkmnd' => '柯尼卡美能达',
        'lg' => '理光',
        'sx' => '三星',
        'xp' => '夏普',
        'zd' => '震旦',
        'lx' => '联想',
        'hp' => '惠普',
        'lixiang' => '理想',
        'lm' => '利盟',
        'bt' => '奔图',
        'aps' => '爱普生',
        'xd' => '兄弟',
        'xindu' => '新都',
        'jsdy' => '基士得耶',
        'wsz' => '-',
    ];

    /*
     * 获取机器来源
     */
    public static function brand($id){
        return isset(self::$brand[$id])? self::$brand[$id]:'出错';
    }

    /*
     * 机器类型
     */
    public static $type = [
        1 => '复印机',
        2 => '打印机',
        3 => '一体机',
        4 => '工程机'
    ];

    public static function type($id)
    {
        return isset(self::$type[$id])? self::$type[$id]:'出错';
    }

    /*
     * 服务列表
     */
    public static $service= [
        1 => '24小时维修',
        2 => '免费更换配件',
        4 => '3次包换新机',
    ];

    public static function service($id)
    {
        return isset(self::$service[$id])? self::$service[$id]:'出错';
    }

    /*
     * 最大尺寸
     */
    public static $maxSize= [
        1 => 'A2',
        2 => 'A3',
        3 => 'A4',
    ];
    public static $black = [
        3 => '3分钱 / 张',
        4 => '4分钱 / 张',
        5 => '5分钱 / 张',
        6 => '6分钱 / 张',
        7 => '7分钱及以上 / 张',
        2 => '2分钱及以下 / 张',
    ];

    public static function maxSize($id)
    {
        return isset(self::$maxSize[$id])? self::$maxSize[$id]:'出错';
    }

    /*
     * 最大速度
     */
    public static $speed= [
        1 => '快速',
        2 => '中速',
        3 => '慢速',
    ];

    /*
     * 最低租期
     */
    public static $rentPeriod = [
        30 => '1个月',
        90 => '3个月',
        180 => '半年',
        360 => '1年',
        720 => '2年及以上',
        0 => '不限制时间',
    ];

    public static function getPeriod($id){
        return isset(self::$rentPeriod[$id])? self::$rentPeriod[$id]:($id.'天');
    }

    public static function speed($id)
    {
        return isset(self::$speed[$id])? self::$speed[$id]:'出错';
    }

    /*
     * 成新
     */
    public static $isNew =[
        10 => '全新',
        5 => '二手',
    ];
    public static function isNew($id)
    {
        return isset(self::$isNew[$id])? self::$isNew[$id]:'出错';
    }

    /*
     * 支持双面
     */
    public static $twoSided =[
        1 => '支持',
        2 => '不支持',
    ];
    public static function twoSided($id)
    {
        return isset(self::$twoSided[$id])? self::$twoSided[$id]:'出错';
    }

    /*
     * 支持彩色
     */
    public static $isColor =[
        1 => '支持',
        2 => '不支持',
    ];
    public static function isColor($id)
    {
        return isset(self::$isColor[$id])? self::$isColor[$id]:'出错';
    }
}