<?php

namespace app\controllers;

use app\models\TblUserWechatSearch;
use app\models\WxUser;
use Yii;

class WxuserController extends \yii\web\Controller
{
    public $layout = 'console';
    public function actionList()
    {
        $searchModel = new TblUserWechatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionSelect()
    {
        return $this->render('select');
    }

    /*
     * 公众号id,用户 openid
     */
    public function actionUpdate($wx_id,$openid)
    {
        $weixin = new WxUser($wx_id);
        $weixin->updateUser($openid);
        return $this->redirect(['list']);
    }

    /*
     * 拉取用户
     */
    public function actionPull()
    {
        $wx = new WxUser(1);
        $wx->pullUser();
    }

}
