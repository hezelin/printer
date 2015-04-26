<?php

namespace app\controllers;
use app\models\WxBase;
use app\models\WxChat;
use Yii;

class AppController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $wx = new WxChat($_GET);

        if (isset($_GET['echostr']))
            $wx->valid();

        $wx->init();
        $reply = '';
        $msgType = empty($wx->msg->MsgType) ? '' : strtolower($wx->msg->MsgType);

        switch ($msgType)
        {
            case 'text':
                //你要处理文本消息代码
                break;
            case 'image':
                //你要处理图文消息代码
                break;
            case 'location':
                //你要处理位置消息代码
                break;
            case 'link':
                //你要处理链接消息代码
                break;
            case 'event':
                //你要处理事件消息代码
                break;
            default:
                //无效消息情况下的处理方式
                break;
        }

        /*
         * 用户关注，保存资料
         */
        if($wx->msg->Event == 'subscribe'){
            $weixin = new WxBase($id);
            $weixin->getUser($wx->msg->FromUserName,true);
        }

        /*
         * 取消关注，删除资料
         */
        if($wx->msg->Event == 'unsubscribe'){
            $weixin = new WxBase($id);
            $weixin->delUser($wx->msg->FromUserName);
        }


        $wx->reply($reply);
    }

    public function actionTest($id)
    {
        $weixin = new WxBase($id);
        $weixin->delUser('oXMyut8n0CaEuXxxKv2mkelk_uaY');

    }

}
