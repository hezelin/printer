<?php

namespace app\controllers;
use app\models\TblStoreSetting;
use app\models\WxBase;
use app\models\WxTemplate;
use app\models\WxUser;
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
                        'actions' => ['add', 'update', 'index','view','delete','start','stop','open','select','createmenu','console'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAdd()
    {
        $model = new TblWeixin();
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post()))
        {
            $model->create_time = time();
            $model->due_time = $model->create_time + 3600*24*7;             // 免费试用 7 天
            $model->uid = Yii::$app->user->id;

            if($model->save()){
                $this->redirect(['view','id'=>$model->id]);
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
        $host = substr(Yii::$app->request->hostInfo,7);
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
        set_time_limit(0);

        $model = $this->findModel($id);
        Yii::$app->session['wechat'] = $model->attributes;


        $transaction = Yii::$app->db->beginTransaction();
        try {
            $wechat = new WxBase($id);
            if( ! $wechat->createMenu($model->name) )
                throw new BadRequestHttpException('创建菜单失败!');

            $wx = new WxUser($id);                              // 拉取旧的 用户微信资料
            $wx->pullUser();

            $model->status = 2;                                 // 默认开通 14天 时间
            $model->due_time = time() + 3600 * 24 * 14;
            $model->save();
                                                                // 店铺 资料 设置
            $setting = TblStoreSetting::find()->where('wx_id=:wid',[':wid'=>$id])->one();
            if(!$setting) $setting = new TblStoreSetting();
            $setting->wx_id = $id;
            $setting->add_time = time();
            $setting->store_name = $model->name;
            $setting->menu_name = $model->name;
            $setting->save();

            $tmp = new WxTemplate($id);
            $tmp->setWechatTmp();                               // 设置模板行业
            $tmp->setWechatTmpId();                             // 设置消息模板

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            return $this->render('//tips/home-status',['tips'=>'开通失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }
        return $this->redirect(['index']);
    }

    /*
     * 数据过期，请重新选择公众号操作
     */
    public function actionSelect()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TblWeixin::find()->where(['enable'=>'Y','uid'=>Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('select',['dataProvider'=>$dataProvider,'callback_url'=>Yii::$app->request->get('url')]);
    }

    /*
     * 刷新菜单
     */
    public function actionCreatemenu($id)
    {
        $wechat = new WxBase($id);
        if( ! $wechat->createMenu() )
            throw new BadRequestHttpException('创建菜单失败!');
        return '菜单创建成功！';
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
