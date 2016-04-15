<?php

namespace app\controllers;

use app\models\Cache;
use app\models\share\Score;
use app\models\WxBase;
use app\models\WxTemplate;
use Yii;

class ShareController extends \yii\web\Controller
{
    public $layout = 'home';
    public $enableCsrfValidation = false;

    public function actionActive()
    {
        $this->layout = 'auicss';
        return $this->render('active');
    }

    public function actionGame()
    {
        return $this->render('game');
    }

    public function actionScore($id)
    {
        return $this->render('score',['id'=>$id]);
    }

    /*
     * 分享方案
     */
    public function actionScheme($id)
    {
        $this->layout = 'auicss';
        $openid = WxBase::openId($id);
        $score = (new \yii\db\Query())
            ->select('score')
            ->from('tbl_user_count')
            ->where('wx_id=:wid and openid=:openid',[':wid'=>$id,':openid'=>$openid])
            ->scalar();
        return $this->render('scheme',['totalScore'=>$score?:0,'id'=>$id]);
    }

    /*
     * 分享公众号
     */
    public function actionMp($id)
    {
        $openid = WxBase::openId($id);
        $data = (new \yii\db\Query())
            ->select('wx_num,name')
            ->from('tbl_weixin')
            ->where(['id'=>$id])
            ->one();

        return $this->render('mp',['data'=>$data,'id'=>$id,'from'=>$openid]);
    }

    /*
     * 来源公众号，有特征码
     */
    public function actionMp2($id)
    {
        $wx_num = (new \yii\db\Query())
            ->select('wx_num')
            ->from('tbl_weixin')
            ->where(['id'=>$id])
            ->scalar();
        return $this->render('mp2',['wx_num'=>$wx_num,'id'=>$id]);
    }

    /*
     * 分享租机方案
     */
    public function actionRent($id)
    {
        $openid = WxBase::openId($id);
        $data = (new \yii\db\Query())
            ->select('name')
            ->from('tbl_weixin')
            ->where(['id'=>$id])
            ->one();

        return $this->render('rent',['data'=>$data,'id'=>$id,'from'=>$openid]);
    }

    /*
     * 分享商城耗材
     */
    public function actionMall($id)
    {
        $openid = WxBase::openId($id);
        $data = (new \yii\db\Query())
            ->select('name')
            ->from('tbl_weixin')
            ->where(['id'=>$id])
            ->one();

        return $this->render('mall',['data'=>$data,'id'=>$id,'from'=>$openid]);
    }

    /*
     * 分享链接提成
     * post origin/type
     */
    public function actionLink($id)
    {
        $model = new Score($id,Yii::$app->request->post('origin'),Yii::$app->request->post('type'),100);
        return $model->change();
    }

}
