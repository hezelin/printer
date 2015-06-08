<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblBrand;
use app\models\TblMachineModel;
use app\models\TblMachineModelSearch;
use app\models\ToolBase;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class ModelController extends Controller
{
    public $layout = '/console';
    /*
     * 添加模型
     */
    public function actionAdd()
    {
        $model = new TblMachineModel();
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            $model->add_time = time();
            $images = json_decode($model->cover_images,true);
            $model->cover = $images[0];

            if($model->save()){
                $params = [
                    'tips'=>'添加模型成功！',
                    'btnText'=>'继续添加',
                    'btnUrl'=>Url::toRoute(['add','url'=>Yii::$app->request->get('url')]),
                ];
                if(Yii::$app->request->get('url')){
                    $params = $params + ['btnText2' =>'返回前一个操作','btnUrl2' =>Url::toRoute(Yii::$app->request->get('url'))];
                }
                return $this->render('//tips/success',$params);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors) );
        }
        $list = TblBrand::findAll(['wx_id'=>Cache::getWid()]);
        $list = $list? ArrayHelper::map($list,'id','name'):[];
        return $this->render('add',['model'=>$model,'list'=>$list]);
    }

    /*
     * 模型列表
     */
    public function actionList()
    {
        $searchModel = new TblMachineModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 模型更新
     */
    public function actionUpdate($id)
    {
        $model = TblMachineModel::findOne($id);
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'修改模型成功！',
                    'btnText'=>'返回模型列表',
                    'btnUrl'=>Url::toRoute(['list','url'=>Yii::$app->request->get('url')]),
                ];
                if(Yii::$app->request->get('url')){
                    $params = $params + ['btnText2' =>'返回前一个操作','btnUrl2' =>Url::toRoute(Yii::$app->request->get('url'))];
                }
                return $this->render('//tips/success',$params);
            }
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors) );
        }

        $list = TblBrand::findAll(['wx_id'=>Cache::getWid()]);
        $list = $list? ArrayHelper::map($list,'id','name'):[];
        return $this->render('update',['model'=>$model,'list'=>$list]);
    }

    /*
     * 模型删除
     */
    public function actionDelete($id)
    {
//        $row = TblMachineModel::findOne($id)->delete();
        $model = TblMachineModel::findOne($id);
        $model->enable = 'N';
        $row = $model->save();
        if($row){
            $params = [
                'tips'=>'删除模型成功！',
                'btnText'=>'返回模型列表',
                'btnUrl'=>Url::toRoute(['list','url'=>Yii::$app->request->get('url')]),
            ];
            if(Yii::$app->request->get('url')){
                $params = $params + ['btnText2' =>'返回前一个操作','btnUrl2' =>Url::toRoute(Yii::$app->request->get('url'))];
            }
            return $this->render('//tips/success',$params);
        }

        return $this->redirect(['list']);
    }

    public function actionView($id)
    {
        $model = TblMachineModel::findOne($id);
        if(!$model)
            throw new NotFoundHttpException('页面不存在');
        return $this->render('view',['model'=>$model]);
    }
}
