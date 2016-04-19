<?php
namespace app\models;
/*
 * 配置的公共类
 */
use yii\helpers\ArrayHelper;

class ConfigBase
{
    /*
     * 公众号 等级
     */
    public static $vip = [
        1 => '试用版',
        2 => '普通版',
        3 => '高级版',
    ];

    public static function getVip($id){
        return isset(self::$vip[$id])? self::$vip[$id]:'出错';
    }


    /*
     * 公众号 状态
     */
    public static $wxStatus = [
        1 => '未开通',
        2 => '运行中',
        3 => '已停止',
        4 => '已到期',
    ];

    public static function getWxStatus($id){
        return isset(self::$wxStatus[$id])? self::$wxStatus[$id]:'出错';
    }

    /*
     * 机器租借状态
     */
    public static $mxStatus = [
        1 => '闲置中',
        2 => '已租借',
        3 => '已报废',
    ];

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
     * 维修进度状态
     */
    public static $fixStatus = [
        1 => '申请中',
        2 => '任务分配中',
        3 => '维修员已接单',
        4 => '维修员到达',
        5 => '故障已确认',
        6 => '申请配件中',
        7 => '配件到达',
        8 => '(完成)等待评价',
        9 => '评价完成',
    ];

    public static function getFixStatus($id){
        return isset(self::$fixStatus[$id])? self::$fixStatus[$id]:'出错';
    }

    /*
     * 维修员进度状态
     */
    public static $fixMaintainStatus = [
        2 => '确认接单',
        3 => '到达签到',
        4 => '故障确定',
        5 => '申请配件',
        6 => '配件到达',
        7 => '维修完成',
    ];

    public static function getFixMaintainStatus($id){
        return isset(self::$fixMaintainStatus[$id])? self::$fixMaintainStatus[$id]:'出错';
    }

    /*
     * 积分来源
     */
    public static $scoreFromStatus = [
        1 => '到店消费',
        2 => '在线消费',
        3 => '分享公众号链接',
        4 => '邀请租机链接',
        5 => '分享耗材链接',
        6 => '邀请租机提成',
        7 => '分享耗材提成'
    ];

    public static function getScoreFromStatus($id){
        return isset(self::$scoreFromStatus[$id])? self::$scoreFromStatus[$id]:'出错';
    }

    /*
     *  获取机器品牌
     */
    public static function getMachineBrand($id='')
    {
        if($id == '0') return '未知';
        $brand = TblBrand::findAll(['wx_id'=>Cache::getWid()]);
        $brand = $brand? ArrayHelper::map($brand,'id','name'):[];
        return $id? $brand[$id]:$brand;
    }

    /*
     * 获取机器型号资料
     */
    public static function getMachineModel($id='')
    {
        $model = (new \yii\db\Query())
            ->select('t.id, p.name, t.type')
            ->from('tbl_machine_model as t')
            ->leftJoin('tbl_brand as p','p.id=t.brand_id')
            ->where('t.enable="Y" and t.wx_id=:wid',[':wid'=>Cache::getWid()])
            ->all();

        $tmp = [];
        if($model){
            foreach($model as $m)
            {
                $tmp[ $m['id'] ] = '( '.$m['name'].' ) ' . $m['type'];
            }
        }
        if($id)
            return isset($tmp[$id])? $tmp[$id]:'机型不存在';
        return $tmp;
    }

    /*
     * 获取机器资料，品牌/型号/系列号
     */
    public static function getMachineInfo($id='',$is_from='')
    {
        $model = (new \yii\db\Query())
            ->select('m.id,m.series_id, p.name, t.type')
            ->from('tbl_machine as m')
            ->leftJoin('tbl_machine_model as t','m.model_id=t.id')
            ->leftJoin('tbl_brand as p','p.id=t.brand_id')
            ->where('t.enable="Y" and m.status=1 and t.wx_id=:wid',[':wid'=>Cache::getWid()]);
        if($is_from)
            $model->andWhere('t.come_from=:from',[':from'=>$is_from]);

        $model = $model->all();

        $tmp = [];
        if($model){
            foreach($model as $m)
            {
                $tmp[ $m['id'] ] = $m['name'].' / '.$m['type'].' / '.$m['series_id'];
            }
        }

        return ( $id && isset($tmp[$id]) )? $tmp[$id]:$tmp;
    }

    /*
     * 机器来源
     */
    public static $machineOrigin = [
        1 => '出租',
        2 => '销售',
        3 => '维修'
    ];

    /*
     * 获取机器来源
     */
    public static function getMachineOrigin($id){
        return isset(self::$machineOrigin[$id])? self::$machineOrigin[$id]:'出错';
    }
}