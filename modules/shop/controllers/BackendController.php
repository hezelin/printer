<?php

namespace app\modules\shop\controllers;

use app\modules\shop\models\TblCategory;
use app\modules\shop\models\TblProduct;
use app\modules\shop\models\TblProductSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use app\models\Cache;
use yii\web\NotFoundHttpException;

class BackendController extends Controller
{
    public $layout = '/console';

    /*
     * 配置 ueditor 图片上传路径
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->request->hostInfo,//图片访问路径前缀
                    "imagePathFormat" => "/uploads/product/{yy}{mm}/{dd}/{time}{rand:6}",//上传保存路径
                    "imageCompressBorder" => 640,
                ],
            ]
        ];
    }
    /*
     * 添加宝贝
     */
    public function actionAdd()
    {
        $model = new TblProduct();

        if ($model->load(Yii::$app->request->post())) {
            $wx_id = Cache::getWid();
            $model->wx_id = $wx_id;
            $model->add_time = time();
            $images = json_decode($model->cover_images,true);
            $model->cover = $images[0];
            if($model->save()){
                return $this->render('//tips/success',[
                    'tips'=>'发布商品成功',
                    'btnText'=>'返回继续发布',
                    'btnUrl'=>Url::toRoute(['add']),
                    'btnText2'=>'查看商品',
                    'btnUrl2'=>Url::toRoute(['view','id'=>$model->id])
                ]);
            }else
                Yii::$app->session->setFlash('error',\app\models\ToolBase::arrayToString($model->errors) );
        }

        return $this->render('add', ['model' => $model]);
    }

    /*
     * 商品列表
     */
    public function actionList()
    {
        $searchModel = new TblProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 商品详情
     */
    public function actionView($id)
    {
        $model = TblProduct::findOne($id);
        if(!$model)
            throw new NotFoundHttpException('页面不存在');
        return $this->render('view',['model'=>$model]);
    }

    /*
     * 商品删除
     */
    public function actionDelete($id)
    {
        $model = TblProduct::findOne($id);
        $model->enable = 'N';
        $model->opera_time = time();
        $model->save();
        return $this->redirect('list');
    }

    /*
     * 商品上下架
     */
    public function actionStatus($id,$status)
    {
        $model = TblProduct::findOne($id);
        $model->status = $status == 1? 2:1;
        $model->save();
        return $this->redirect('list');
    }

    /*
     * 修改商品
     */
    public function actionUpdate($id)
    {
        $model = TblProduct::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $wx_id = Cache::getWid();
            $model->wx_id = $wx_id;
            $model->add_time = time();
            $images = json_decode($model->cover_images,true);
            $model->cover = $images[0];
            if($model->save()){
                return $this->render('//tips/success',[
                    'tips'=>'编辑商品成功',
                    'btnText'=>'返回继续操作',
                    'btnUrl'=>Url::toRoute(['list']),
                    'btnText2'=>'查看商品',
                    'btnUrl2'=>Url::toRoute(['view','id'=>$model->id])
                ]);
            }else
                Yii::$app->session->setFlash('error',\app\models\ToolBase::arrayToString($model->errors) );
        }

        $category = TblCategory::findAll(['wx_id'=>Cache::getWid()]);
        $category = $category? ArrayHelper::map($category,'id','name'):[];
        return $this->render('update', ['model' => $model,'category'=>$category]);
    }
}
