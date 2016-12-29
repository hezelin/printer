<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\models\TblUserMaintain;
use app\models\TblUserMaintainSearch;
use yii\filters\VerbFilter;
use Yii;


class StaffController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 解绑必须 post 提交
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'unbind' => ['post'],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionUnbind($wx_id, $openid)
    {
        //[20161228 新增状态字段 删除 ：status = 11
        //$this->findModel($wx_id, $openid)->delete();
        //return $this->redirect(['list']);

        $model = $this->findModel($wx_id, $openid);
        $model->status = 11;
        if($model->save())
            return $this->redirect(['list']);
        else
            return $this->render('//tips/error', [
                'tips' => '操作失败！',
                'btnText' => '重试',
                'btnUrl' => \yii\helpers\Url::toRoute(['/staff/list'])
            ]);
        //20161228]
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $searchModel = new TblUserMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionUpdate($wx_id,$openid)
    {
        $model = $this->findModel($wx_id,$openid);
        if($model->load(Yii::$app->request->post()) && $model->save())
            $this->redirect(['list']);

        return $this->render('update',['model'=>$model]);
    }

    public function actionView()
    {
        return $this->render('view');
    }

    protected function findModel($wx_id, $openid)
    {
        if (($model = TblUserMaintain::findOne(['wx_id' => $wx_id, 'openid' => $openid, 'status' => 10])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
