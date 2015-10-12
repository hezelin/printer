<?php
/*
 * 授权登录 获取微信资料接口
 * 研趣网微信官网使用
 */
namespace app\controllers;

use app\models\Curl;
use app\models\WxBase;
use yii\helpers\Url;
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

}
