<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblWeixin;
use yii\web\NotFoundHttpException;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * behaviors 验证用户登录
     */
    public function actionView($id)
    {
        $model = TblWeixin::find()
            ->where(['id'=>$id,'enable'=>'Y'])
            ->asArray()
            ->one();

        if( $model == null )
            throw new NotFoundHttpException('查看的页面不存在');

        /*
         * 保存 微信id,24小时
         */
        Cache::setValue('u:'.Yii::$app->user->id.':wid', $model['id'], 'PX', 60*60*24);

        Yii::$app->session['wechat'] = $model;

        // 是否选择公众号，跳转
        if( Yii::$app->request->get('url')) $this->redirect( Yii::$app->request->get('url') );

        return $this->render('view');
    }
}
