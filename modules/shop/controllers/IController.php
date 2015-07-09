<?php
/*
 * 微信端 商品
 */

namespace app\modules\shop\controllers;

use app\models\ToolBase;
use app\models\WxBase;
use app\modules\shop\models\TblShopCart;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;


class IController extends Controller
{
    public $layout = 'shop';
    public $enableCsrfValidation = false;
    /*
     * 我的购物车
     */
    public function actionCart($id)
    {
        return $this->render('cart');
    }

    /*
     * 我的订单
     */
    public function actionOrder($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('order_id,order_data,total_price,pay_score,order_status,pay_status,add_time')
            ->from('tbl_shop_order')
            ->where('openid=:openid and wx_id=:wid and enable="Y"',[':openid'=>$openid,':wid'=>$id])
            ->all();
        if(!$model)
            return $this->render('//tips/homestatus',['tips'=>'还没有订单！',
                'btnText'=>'去商城购物','btnUrl'=>Url::toRoute(['/shop/item/list','id'=>$id])]);

        foreach($model as $k=>&$r){
            $tmp = json_decode($r['order_data'],true);
            if(is_array($tmp) && isset($tmp[0])){
                $num = 0;
                foreach($tmp as $t)
                    $num += (int)$t['item_nums'];
                $r['total_num'] = $num;
                $r['cover'] = $tmp[0]['cover'];
            }
            else
                unset($model[$k]);
        }

        return $this->render('order',['model'=>$model,'id'=>$id]);
    }

    /*
     * 我的积分
     */
    public function actionScore($id)
    {
        return $this->render('score');
    }

}