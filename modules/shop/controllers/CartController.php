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


class CartController extends Controller
{
    public $layout = 'shop';
    public $enableCsrfValidation = false;
    /*
     * 添加购物车
     */
    public function actionAdd($id)
    {
        if( ($item_id = Yii::$app->request->post('item_id')) && ($openid = Yii::$app->request->post('openid')) ){
            $model = TblShopCart::find()->where(['item_id'=>$item_id,'openid'=>$openid,'wx_id'=>$id,'enable'=>'Y'])->one();
            if($model){
                $model->item_nums = $model->item_nums + 1;
            }else{
                $model = new TblShopCart();
                $model->wx_id = $id;
                $model->openid = $openid;
                $model->create_time = time();
                $model->item_id = $item_id;
            }
            if($model->save())
                echo json_encode(['status'=>1]);
            else
                echo json_encode(['status'=>0,'msg'=>'入库失败!']);

        }else
            echo json_encode(['status'=>0,'msg'=>'数据不合法!']);
    }
    /*
     * 查询产品列表
     */
    public function actionList($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id,t.item_nums,t.item_id,p.cover,p.price,p.name')
            ->from('tbl_shop_cart as t')
            ->leftJoin('tbl_product as p','t.item_id=p.id')
            ->where('t.openid=:openid and t.enable="Y" and t.wx_id=:wid',[':openid'=>$openid,':wid'=>$id])
            ->all();

        $total = $totalPrice =0;
        foreach($model as &$m)
        {
            $total += $m['item_nums'];
            $totalPrice += $m['price']*$m['item_nums'];
        }
        if(!$model)
            return $this->render('//tips/home-status',['tips'=>'购物车是空的！',
                'btnText'=>'去商城购物','btnUrl'=>Url::toRoute(['/shop/item/list','id'=>$id])]);

        return $this->render('list',['model'=>$model,'total'=>$total,'totalPrice'=>$totalPrice,'id'=>$id]);
    }

    public function actionOpera()
    {
        if($item_id = Yii::$app->request->post('id')){
            $model = TblShopCart::findOne($item_id);
            $num = Yii::$app->request->post('num');
            if(!$model){
                Yii::$app->end(json_encode(['status'=>0,'msg'=>'服务器错误22']));
            }
            if($num) {
                $model->item_nums = $num;
                if( !$model->save() )
                    Yii::$app->end(json_encode(['status'=>0,'msg'=>'服务器错误44']));
            }else{
                if( !$model->delete() )
                    Yii::$app->end(json_encode(['status'=>0,'msg'=>'服务器错误33']));
            }

            echo json_encode(['status'=>1]);
        }
    }

}