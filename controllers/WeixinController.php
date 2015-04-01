<?php

namespace app\controllers;
use Yii;
use app\models\TblWeixin;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class WeixinController extends \yii\web\Controller
{
    public $layout = 'weixin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
//        if (Yii::$app->user->isGuest)  return $this->redirect(['auth/login','url'=>'/weixin/add']);
        $model = new TblWeixin();
        if($model->load(Yii::$app->request->post()))
        {
            $model->create_time = time();
            $model->due_time = $model->create_time + 3600*24*7;             // 免费试用 7 天
            $model->uid = Yii::$app->user->id;

            if($model->save()){
                $this->redirect('index');
            }else{
                print_r($model->errors);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TblWeixin::find(),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index',['dataProvider'=>$dataProvider]);
    }

    public function actionView($id)
    {
        $host = Yii::$app->request->hostInfo;
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'wxToken' => md5($model->id . Yii::$app->params['wxTokenSalt']),
            'host' => $host,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*
     * 启动公众号功能
     */
    public function actionStart($id)
    {
        sleep(1);
        echo json_encode(['status'=>1]);
    }

    /*
     * 停止公众号
     */
    public function actionStop($id)
    {
        sleep(1);
        echo json_encode(['status'=>1]);
    }

    protected function findModel($id)
    {
        if (($model = TblWeixin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
