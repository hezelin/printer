<?php

namespace app\controllers;
use app\models\WxBase;
use Yii;
use app\models\TblWeixin;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class WeixinController extends \yii\web\Controller
{
    public $layout = 'weixin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['add', 'update', 'index','view','delete','start','stop'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add', 'update', 'index','view','delete','start','stop','open'],
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
            'query' => TblWeixin::find()->where(['enable'=>'Y','uid'=>Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        if(  ! $dataProvider->getTotalCount() )
            return $this->render('//tips/success',[
                'tips' => '亲，你还没有添加任何公众号！',
                'btnText' => '添加公众号',
                'btnUrl' => Url::toRoute(['weixin/add'])
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
        $model = $this->findModel($id);
        $model->enable = 'N';
        $model->save();
        return $this->redirect(['index']);
    }

    /*
     * 启动公众号功能
     */
    public function actionStart($id)
    {
        $model = $this->findModel($id);
        $model->status = 2;
        if($model->save())
            return json_encode(['status'=>1]);
    }

    /*
     * 停止公众号
     */
    public function actionStop($id)
    {
        $model = $this->findModel($id);
        $model->status = 3;
        if($model->save())
            return json_encode(['status'=>1]);
    }

    /*
     * 续费
     */
    public function actionRenew($id)
    {
        return $this->render('renew');
    }

    /*
     * 开通 , 设置菜单 + 开通
     */
    public function actionOpen($id)
    {

        $model = $this->findModel($id);

        if(!Yii::$app->session['wechat']){
            Yii::$app->session['wechat'] = $model->attributes;
        }

        $wechat = new WxBase($id);
        if( ! $wechat->createMenu() )
            throw new BadRequestHttpException('创建菜单失败!');

        $model->status = 2;
        $model->due_time = time() + 3600 * 24 * 7;
        if($model->save()){
            return $this->redirect(['index']);
        }
    }


    /*
     * 找到
     */
    protected function findModel($id)
    {
        if (($model = TblWeixin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
