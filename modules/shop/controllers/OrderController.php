<?php
/*
 * 微信端 商品
 */

namespace app\modules\shop\controllers;

use app\models\TblUserCount;
use app\models\WxBase;
use app\modules\shop\models\TblShopOrder;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;


class OrderController extends Controller
{
    public $layout = 'shop';
    public $enableCsrfValidation = false;
    /*
     * 订单提交，微信id
     */
    public function actionPut($id)
    {
        if( Yii::$app->request->post('TblShopOrder'))
        {
            $model = new TblShopOrder();
            $model->load(Yii::$app->request->post());
            $model->add_time = time();
            $model->wx_id = $id;
            $model->pay_score = (int)$model->pay_score;
            $model->order_id = date('YmdHis',time()).rand(1000,9999);
            if($model->pay_status == 3)         // 在线支付，更改为等待付款
                $model->order_status = 3;

            $openid = $model['openid'];
            $items = (new \yii\db\Query())
                ->select('t.item_id,t.item_nums,p.name,p.cover,p.price')
                ->from('tbl_shop_cart as t')
                ->leftJoin('tbl_product as p','t.item_id=p.id')
                ->where('t.openid=:openid and t.enable="Y"',[':openid'=>$openid])
                ->orderBy('item_id')
                ->all();
            if(!$items)
                return $this->render('//tips/home-status',['tips'=>'请不要重复提交！',
                    'btnText'=>'返回','btnUrl'=>Url::toRoute(['/shop/i/order','id'=>$id])]);

            $model->order_data = json_encode($items);

            // 拼接 mysql 批量更新 tbl_product 数量语句
            $sql2When = '';
            $itemIds = [];
            foreach( ArrayHelper::map($items,'item_id','item_nums') as $k=>$v){
                $sql2When .= " WHEN {$k} THEN amount-{$v} ";
                $itemIds[] = $k;
            }
            $itemIds = '('.implode(',',$itemIds).')';
            $sql2 = "UPDATE tbl_product SET amount = CASE id $sql2When END WHERE id IN $itemIds";

            // 验证价格是否正确
            $totalPrice = $model->total_price + $model->freight + $model->pay_score/100;

            foreach($items as &$r)
            {
                $totalPrice -= $r['price']*$r['item_nums'];
            }
            if( $totalPrice)
                return $this->render('//tips/home-status',['tips'=>'价格出错！',
                    'btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            try {
                if(!$model->save())
                    throw new Exception('保存订单出错');                     // 订单保存

                // 删除购物车
                $sql1 = "DELETE FROM `tbl_shop_cart` WHERE openid='$openid' and wx_id=$id";
                $connection->createCommand($sql1)->execute();

//                更改积分
                if($model->pay_score > 0){
                    $score = TblUserCount::findOne(['wx_id'=>$id,'openid'=>$openid]);
                    if($score->score < $model->pay_score)
                        return $this->render('//tips/home-status',['tips'=>'积分不够！',
                            'btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
                    $score->score = $score->score - $model->pay_score;
                    if(!$score->save())
                        throw new Exception('更改积分出错');
                }

//                更改商城库存
                $connection->createCommand($sql2)->execute();

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
//                echo $e;
                return $this->render('//tips/home-status',['tips'=>'库存不足，下单失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
            }

            return $this->render('pay',['id'=>$id,'order_id'=>$model->order_id,'price'=>$model->total_price,'payStatus'=>$model->pay_status]);

//            return $this->render('//tips/home-status',['tips'=>'订单提交成功','btnText'=>'返回我的订单','btnUrl'=>Url::toRoute(['/shop/i/order','id'=>$id])]);
        }

//        $openid = 'oXMyut8n0CaEuXxxKv2mkelk_uaY';
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id,t.item_nums,p.cover,p.price,p.name,p.use_score,p.give_score')
            ->from('tbl_shop_cart as t')
            ->leftJoin('tbl_product as p','t.item_id=p.id')
            ->where('t.openid=:openid and t.enable="Y"',[':openid'=>$openid])
            ->all();

        $total = ['num'=>0,'price'=>0,'giveScore'=>0,'useScore'=>0,'score'=>0];                // 金额、积分
        $address = (new \yii\db\Query())                                                       // 查询 地址
        ->select('id,name,phone,city,address')
        ->from('tbl_shop_address')
        ->where('enable="Y" and openid=:openid',[':openid'=>$openid])
        ->limit(1)
        ->orderBy('add_time desc')
        ->one();

        $total['score'] = (new \yii\db\Query())->select('score')->from('tbl_user_count')->where(['wx_id'=>$id,'openid'=>$openid])->scalar() ? :0;

        foreach($model as &$m)
        {
            $total['num'] += $m['item_nums'];
            $total['price'] += $m['price']*$m['item_nums'];
            $total['giveScore'] += $m['item_nums']*$m['give_score'];
            $total['useScore'] += $m['item_nums']*$m['use_score'];
        }

        return $this->render('put',['model'=>$model,'address'=>$address,'total'=>$total,'openid'=>$openid,'id'=>$id]);
    }

    /*
     * 订单详情
     */
    public function actionDetail($id,$order_id)
    {
        $model = (new \yii\db\Query())
            ->select('order_id,order_data,remark,check_word,freight,total_price,order_status,pay_status,pay_score,express,t.add_time,
                a.name,a.phone,a.city,a.address')
            ->from('tbl_shop_order as t')
            ->leftJoin('tbl_shop_address as a','t.address_id=a.id')
            ->where('t.order_id=:order_id and t.enable="Y"',[':order_id'=>$order_id])
            ->one();
        if(!$model)
            return $this->render('//tips/home-status',[
                'tips'=>'订单不存在！',
                'btnText'=>'返回我的订单',
                'btnUrl'=>Url::toRoute(['/shop/i/order','id'=>$id])]
            );

        $item = json_decode($model['order_data'],true);

        return $this->render('detail',['model'=>$model,'id'=>$id,'item'=>$item]);
    }

    /*
     * 订单付款
     */
    public function actionPay($id,$order_id)
    {
        $model = TblShopOrder::findOne($order_id);
        if(!$model)
            return $this->render('//tips/home-status',['tips'=>'订单不存在!','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1)']);
        $price = $model->total_price;
        $payStatus = $model->pay_status;
        unset($model);
        return $this->render('pay',['id'=>$id,'order_id'=>$order_id,'price'=>$price,'payStatus'=>$payStatus]);
    }

    /*
     * 更改订单状态，删除、取消
     */
    public function actionStatus($id,$order_id)
    {
        if( $status = Yii::$app->request->post('status')){
            $model = TblShopOrder::findOne($order_id);
            if( $status == 'cancel'){
                $model->order_status = 8;
            }elseif($status == 'delete')
                $model->enable = 'N';
            else
                return json_encode(['status'=>0,'msg'=>'参数错误!']);

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

            try {
                $model->save();                     //  更改订单状态

//                更改产品库存,取消订单  或者状态小于3的 删除订单
                if($status == 'cancel' || ($status == 'delete' && $model->order_status < 3)){
                    $sql2When = '';
                    $itemIds = [];
                    $itemData = json_decode($model->order_data,true);
                    foreach( $itemData as $v){
                        $sql2When .= " WHEN {$v['item_id']} THEN amount+{$v['item_nums']} ";
                        $itemIds[] = $v['item_id'];
                    }
                    $itemIds = '('.implode(',',$itemIds).')';
                    $sql2 = "UPDATE tbl_product SET amount = CASE id $sql2When END WHERE id IN $itemIds";
                    $connection->createCommand($sql2)->execute();
                }

//                更改积分
                if($model->pay_score > 0){
                    $score = TblUserCount::findOne(['wx_id'=>$id,'openid'=>$model->openid]);
                    $score->score = $score->score + $model->pay_score;
                    $score->save();
                }

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                return json_encode(['status'=>0,'msg'=>'入库失败!']);
            }
            return json_encode(['status'=>1]);
        }
        return json_encode(['status'=>1]);
    }

    /*
     * 订单 物流状态
     */
    public function actionExpress($id,$order_id)
    {
        echo '这里是订单物流状态';
    }
}