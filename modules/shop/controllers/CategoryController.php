<?php

namespace app\modules\shop\controllers;

use app\models\Cache;
use app\models\TblCategory;
use app\models\ToolBase;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class CategoryController extends Controller
{
    public $layout = '/console';
    public function actionAdd()
    {
        $model = new TblCategory();
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'添加目录成功！',
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
            'query' => TblCategory::find()->where(['wx_id'=>Cache::getWid()]),
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
        $model = TblCategory::findOne($id);
        if ($model->load(Yii::$app->request->post())){
            $model->wx_id = Cache::getWid();
            if($model->save()){
                $params = [
                    'tips'=>'修改类目成功！',
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
        $row = TblCategory::findOne($id)->delete();
        if($row){
            $params = [
                'tips'=>'删除类目成功！',
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
