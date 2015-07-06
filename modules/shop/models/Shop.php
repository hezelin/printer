<?php
namespace app\modules\shop\models;

use yii\helpers\ArrayHelper;
use app\models\Cache;
/**
 * 商城状态类
 */

class Shop {

    /*
     * 获取类目
     */
    public static function getCategory($id='',$wx_id='')
    {
        $category = TblCategory::findAll(['wx_id'=>$wx_id? :Cache::getWid()]);
        $category = $category? ArrayHelper::map($category,'id','name'):[];
        return $id? $category[$id]:$category;
    }

    /*
     * 获取passthroughWidget  类目
     */
    public static function getMenu($wx_id='')
    {
        $category = TblCategory::findAll(['wx_id'=>$wx_id? :Cache::getWid()]);
        $tmp = [
            ['name'=>'全部','key'=>'all','active'=>true]
        ];
        if($category){
            foreach($category as $c){
                $tmp[]=[
                    'name'=>$c['name'],
                    'key'=>$c['id']
                ];
            }
        }
        return $tmp;
    }

    /*
     * 获取 携带配件状态
     */
    public static function getParts($id='')
    {
        $data = [1=>'申请中',2=>'携带中',3=>'发送中',4=>'已到达',10=>'已绑定',11=>'已取消',12=>'已回收'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }

    /*
     * 获取 付款方式
     */
    public static function getPayStatus($id='')
    {
        $data = [1=>'货到付款',2=>'上门自取',3=>'在线支付',4=>'付款完成'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }

    /*
     * 获取 订单状态
     */
    public static function getOrderStatus($id='')
    {
        $data = [ 1=>'审核中', 2=>'审核失败', 3=>'等待付款', 4=>'等待发货', 5=>'已发货', 6=>'订单完成', 7=>'订单已取消', 8=>'订单已取消(系统)'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }
} 