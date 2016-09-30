<?php
/*
 * 微信端 商品
 */

namespace app\modules\shop\controllers;

use app\models\WxBase;
use yii\helpers\ArrayHelper;
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
        $this->layout = '/auicss';
        $len = Yii::$app->request->get('len')? : 10;
        $cate = Yii::$app->request->get('category');
        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover,t.price')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.wx_id=:wid',[':wid'=>$id])
            ->andWhere(['>','t.amount',0])
            ->limit($len)
            ->orderBy('t.id desc');
        if(Yii::$app->request->get('startId'))
            $model->andWhere(['<','t.id',Yii::$app->request->get('startId')]);
        if(Yii::$app->request->get('q'))
            $model->andWhere(['like','t.name',Yii::$app->request->get('q')]);


        if($cate && $cate != 'all')
        {
            $model->andWhere(['t.category_id'=>$cate]);
        }

        $model = $model->all();

        foreach($model as &$m)
            $m['cover'] = str_replace('/s/','/m/',$m['cover']);

        if(Yii::$app->request->get('format') == 'json'){
            return $model? json_encode([
                'status'=>1,
                'data'=>$model,
                'len'=>count($model),
                'startId'=>$model[count($model)-1]['id'],
            ]):json_encode(['status'=>0,'msg'=>'没有数据了','startId'=>0]);
        }

        $category = (new \yii\db\Query())
            ->select('id,name')
            ->from('tbl_category')
            ->where(['wx_id'=>$id])
            ->all();
        $category = $category? ArrayHelper::map($category,'id','name'):[];

        $categoryName = '所有商品';
        if( $cate && $cate != 'all')
        {
            $categoryName = isset($category[$cate])? $category[$cate]:'所有商品';
        }
        $startId = $model? $model[count($model)-1]['id']:0;

        return $this->render('list',[
            'model'=>$model,
            'startId'=>$startId,
            'id'=>$id,
            'len'=>count($model),
            'category'=>$category,
            'categoryName'=>$categoryName,
        ]);
    }

    /*
     * 详情
     */
    public function actionDetail($id,$item_id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover_images,t.price,t.market_price,t.describe,t.add_attr')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.id=:id',[':id'=>$item_id])
            ->one();
        $model['cover_images'] = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['big_cover_images'] = [];
        foreach($model['cover_images'] as $img){
            $model['big_cover_images'][] = Yii::$app->request->hostInfo.str_replace('/m/','/o/',$img);
        }

        $model['else_attr'] = $model['add_attr']? json_decode($model['add_attr'],true):'';

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