<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblBrand;
use app\models\ToolBase;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class MachinebrandController extends Controller
{
    public $layout = '/console';
    public function actionAdd()
    {
        $model = new TblBrand();
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'添加品牌成功！',
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
        return $this->render('add',['model'=>$model]);
    }

    public function actionList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TblBrand::find()->where(['wx_id'=>Cache::getWid()])->andWhere(['>','id',0]),
            'pagination' => [
                'pageSize' => 15,
            ],
             'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
        ]);
        return $this->render('list',['dataProvider'=>$dataProvider]);
    }

    /*
     * 类目的id
     */
    public function actionUpdate($id)
    {
        $model = TblBrand::findOne($id);
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'修改品牌成功！',
                    'btnText'=>'返回类目列表',
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

    public function actionDelete($id)
    {
        $row = TblBrand::findOne($id)->delete();
        if($row){
            $params = [
                'tips'=>'删除品牌成功！',
                'btnText'=>'返回类目列表',
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
