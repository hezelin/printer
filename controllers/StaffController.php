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
        $this->findModel($wx_id, $openid)->delete();
        return $this->redirect(['list']);
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
        if (($model = TblUserMaintain::findOne(['wx_id' => $wx_id, 'openid' => $openid])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
