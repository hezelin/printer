<?php
/*
 * 微信端 商品
 */

namespace app\modules\shop\controllers;

use app\models\WxBase;
use yii\web\Controller;
use Yii;


class ItemController extends Controller
{
    public $layout = 'shop';

    /*
     * 查询产品列表
     */
    public function actionList($id)
    {
        $len = Yii::$app->request->get('len')? : 10;

        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover,t.price')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.wx_id=:wid',[':wid'=>$id])
            ->limit($len)
            ->orderBy('t.id desc');
        if(Yii::$app->request->get('startId'))
            $model->andWhere(['<','t.id',Yii::$app->request->get('startId')]);
        if(Yii::$app->request->get('q'))
            $model->andWhere(['like','t.name',Yii::$app->request->get('q')]);
        if(Yii::$app->request->get('key') && Yii::$app->request->get('key') != 'all')
            $model->andWhere('t.category_id=:cate',[':cate'=>Yii::$app->request->get('key')]);

        $model = $model->all();

        if(Yii::$app->request->get('format') == 'json'){
            return $model? json_encode([
                'status'=>1,
                'data'=>$model,
                'len'=>count($model),
                'startId'=>$model[count($model)-1]['id'],
            ]):json_encode(['status'=>0,'msg'=>'没有数据了','startId'=>0]);
        }

        $startId = $model? $model[count($model)-1]['id']:0;

        return $this->render('list',[
            'model'=>$model,
            'startId'=>$startId,
            'id'=>$id,
            'category'=>\app\modules\shop\models\Shop::getMenu($id),
        ]);
    }

    /*
     * 详情
     */
    public function actionDetail($id,$item_id)
    {
        $openid = WxBase::openId($id);
//        $openid = 'oXMyut8n0CaEuXxxKv2mkelk_uaY';

        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover_images,t.price,t.market_price,t.describe,t.add_attr')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.id=:id',[':id'=>$item_id])
            ->one();
        $model['cover_images'] = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['else_attr'] = json_decode($model['add_attr'],true);

        return $this->render('detail',['model'=>$model,'id'=>$id,'openid'=>$openid]);
    }

    /*
     * 商城首页
     */
    public function actionHome($id)
    {
        return $this->render('home');
    }
}