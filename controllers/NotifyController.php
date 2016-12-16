<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblActivity;
use app\models\TblNotifyLog;
use app\models\TblNotifyLogSearch;
use app\models\TblUserWechat;
use app\models\WxChat;
use app\models\WxTemplate;
use Yii;
use yii\helpers\Url;

class NotifyController extends \yii\web\Controller
{
    public $layout = 'console';

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

    public function actionList()
    {
        $searchModel = new TblNotifyLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    /*
     * $id 公众号id
     * $openid 接收者id
     */
    public function actionSend()
    {
        $id = Yii::$app->request->get('id')? :Cache::getWid();
        $openid = \Yii::$app->request->get('openid');

        $model = new TblNotifyLog();

        if($model->load(Yii::$app->request->post())){
            $model->openid = $openid;
            $model->add_time = time();
            $model->from_id = Yii::$app->user->id;
            $model->wx_id = $id;
            if( $model->save() ){
                //[20161129 执行微信窗口推送
//                $tpl = new WxTemplate($id);
//                $tpl -> sendNotify($openid,
//                    '您好，你现有新的通知：',
//                    $model->text,
//                    date("m月d日 H:i",time()),
//                    "没有留言！"
//                );

                //20161129 执行微信窗口推送]

                //[20161130  改为文字发送
//                $par = [];
//                $par[] = ['FromUserName'=>$id, 'ToUserName'=>$openid];
//                $msg = [];
//                $wx = new WxChat($msg[] = $par);
//                $wx->makeText($model->text);
                //20161130]


                //[20161202 修改模板消息
                $tpl = new WxTemplate($id);
                $tpl ->sendOrderNotify(
                    $openid,
                    '您好，你现有新的通知：',
                    $model->text,
                    '租赁商',
                    date('m月d日 H:i'),
                    '没有留言'
                );

                return $this->render('//tips/success', [
                    'tips' => '通知发送成功！',
                    'btnText' => '继续发送',
                    'btnUrl' => \yii\helpers\Url::toRoute(['/notify/send'])
                ]);


                //201612020]

            }
            else
                Yii::$app->session->setFlash('error',\app\models\ToolBase::arrayToString($model->errors));
        }

        $user = $openid? TblUserWechat::findOne(['wx_id'=>$id,'openid'=>$openid]):NULL;
        return $this->render('send',['user'=>$user,'model'=>$model]);
    }

    /*
     * 通知消息 id
     */
    public function actionDelete($id)
    {
        $model = TblNotifyLog::findOne($id);
        $model->enable = 'N';
        $model->save();
        $this->redirect(['list']);
    }

    /*
     * 最新活动
     */
    public function actionActivity()
    {
        $wxId = Cache::getWid();
        $model = TblActivity::findOne($wxId);
        if($model == null)
        {
            $model = new TblActivity();
            $model->wx_id = $wxId;
        }

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            $model->created_at = time();
            if($model->save())
                Yii::$app->session->setFlash('success','保存成功');
            else
                Yii::$app->session->setFlash('error','保存失败');
        }
        return $this->render('activity',['model'=>$model]);
    }
}
