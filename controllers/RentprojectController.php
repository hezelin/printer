<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblBrand;
use app\models\TblMachineModel;
use app\models\TblMachineModelSearch;
use app\models\TblMachineRentProject;
use app\models\TblMachineRentProjectSearch;
use app\models\ToolBase;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class RentprojectController extends Controller
{
    public $layout = '/console';
    /*
     * 添加模型
     */
    public function actionAdd()
    {
        $model = new TblMachineRentProject();
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            $model->add_time = time();

            if($model->save()){
                $params = [
                    'tips'=>'添加租借方案成功！',
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
        $model->is_show = 1;
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 模型列表
     */
    public function actionList()
    {
        $searchModel = new TblMachineRentProjectSearch();
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
        $model = TblMachineRentProject::findOne($id);
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'修改租机方案成功！',
                    'btnText'=>'返回租机方案列表',
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

        return $this->render('update',['model'=>$model]);
    }

    /*
     * 模型删除
     */
    public function actionDelete($id)
    {
        $row = TblMachineRentProject::findOne($id)->delete();
        /*$model = TblMachineRentProject::findOne($id);
        $model->is_show = 0;
        $row = $model->save();*/
        if($row){
            $params = [
                'tips'=>'删除租机方案成功！',
                'btnText'=>'返回租机方案列表',
                'btnUrl'=>Url::toRoute(['list','url'=>Yii::$app->request->get('url')]),
            ];
            if(Yii::$app->request->get('url')){
                $params = $params + ['btnText2' =>'返回前一个操作','btnUrl2' =>Url::toRoute(Yii::$app->request->get('url'))];
            }
            return $this->render('//tips/success',$params);
        }

        return $this->redirect(['list']);
    }
}
