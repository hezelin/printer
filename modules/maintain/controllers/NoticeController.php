<?php
/*
 * 维修员通知页面
 */
namespace app\modules\maintain\controllers;

use app\models\TblNotifyLog;
use app\models\WxBase;
use yii\web\Controller;


class NoticeController extends Controller
{
    public $layout = '/auicss';

    /*
     * 最新通知
     * $id 公众号
     */
    public function actionIndex($id)
    {
        $openid = WxBase::openId($id);
        $model = TblNotifyLog::find()->where(['wx_id'=>$id,'openid'=>$openid,'enable'=>'Y'])->orderBy('id desc')->all();
        return $this->render('index',['model'=>$model,'count'=>count($model)]);
    }

}
