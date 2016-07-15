<?php
/*
 * 授权登录 获取微信资料接口
 * 研趣网微信官网使用
 */
namespace app\controllers;

use app\models\TblZujiApply;
use app\models\WxBase;
use yii\web\Controller;


class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    /*
     * 获取微信用户资料
     */
    public function actionUser($callback)
    {
        return WxBase::webUser(1,$callback);
    }

    /*
     * 获取微信 openid
     */
    public function actionOpenid($callback)
    {
        return WxBase::openId(1,false,$callback);
    }

    /*
     * 临时开放 openid
     */
    public function actionTmpOpenid($callback)
    {
        return WxBase::openId(14,false,$callback);
    }

    /*
     * 临时获取用户资料
     */
    public function actionTmpUser($callback)
    {
        return WxBase::webUser(14,$callback);
    }

    /*
     * aandesigners:1e54998a18b15e4449d525feb1d7ac6f
    contName:jlkjlk
    contPhone:jlkjlk
    contEmail:jlkjlk@qq.com
    contCompanyName:lkjkl
    contContent:jlkj;lklklkkkkkklkk
    */
    public function actionContact()
    {
        if($post = \Yii::$app->request->post())
        {
            $model = new TblZujiApply();
            $model->name = $post['contName'];
            $model->phone = $post['contPhone'];
            $model->email = $post['contEmail'];
            $model->company = $post['contCompanyName'];
            $model->info = $post['contContent'];
            if($model->save())
                echo json_encode(['status'=>'success']);
            else
                echo json_encode(['status'=>'error','message'=>'出现未知错误！']);
        }else
            echo json_encode(['status'=>'invalid','message'=>'参数不合法']);
    }

}
