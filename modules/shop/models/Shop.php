<?php
namespace app\modules\shop\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
     * （等待付款 or 审核中） -> (审核失败 | 等待发货 | 等待取货） -> ( 已发货 ， 订单完成 ）->  ( 订单完成 )
     */
    public static function getOrderStatus($id='')
    {
        $data = [ 1=>'审核中', 2=>'审核失败', 3=>'等待付款', 4=>'等待取货',5=>'等待发货', 6=>'已发货', 7=>'订单完成', 8=>'订单已取消', 9=>'订单已取消(系统)'];
        if(!empty($id))
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }

    /*
     * 快递列表
     */
    public static function getExpress($id='')
    {
        $data = ['业务员派送','顺丰','邮政','申通','韵达','圆通','中通','汇通'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }
    /*
     * 根据订单状态，返回订单按钮
     */
    public static function getOrderBtn($order_status,$order_id,$wx_id)
    {
        switch($order_status){
            case 1:
            case 2:
                return '<a class="order-btn order-btn-left btn-failure">'.self::getOrderStatus($order_status).'</a>
                                <a data-order="'.$order_id.'" data-type="delete" class="order-btn order-btn-ajax">删除订单</a>';
                
            case 3:
                return '<a href="'.Url::toRoute(['/shop/order/pay','id'=>$wx_id,'order_id'=>$order_id]).'" class="order-btn order-btn-left">'.self::getOrderStatus($order_status).'</a>
                      <a data-order="'.$order_id.'" data-type="cancel" class="order-btn order-btn-ajax">取消订单</a>';
                
            case 4:
            case 5:
                return '<a class="order-btn order-btn-left btn-failure">'.self::getOrderStatus($order_status).'</a>
                                    <a data-order="'.$order_id.'" data-type="cancel" class="order-btn order-btn-ajax">取消订单</a>';
                
            case 6:
                return '<a class="order-btn order-btn-left btn-failure">'.self::getOrderStatus($order_status).'</a>
                                    <a href="'.Url::toRoute(['/shop/order/express','id'=>$wx_id,'order_id'=>$order_id]).'" class="order-btn">查看物流</a>';
                
            case 7:
            case 8:
            case 9:
                return '<a class="order-btn order-btn-left btn-failure">'.self::getOrderStatus($order_status).'</a>
                                    <a data-order="'.$order_id.'" data-type="delete" class="order-btn order-btn-ajax">删除订单</a>';
                
        }
    }
} 