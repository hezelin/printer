<?php

namespace app\controllers;
use app\models\WxChat;
use Yii;

class AppController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        $wx = new WxChat($_GET);
        $wx->token = md5( Yii::$app->params['token'].$wx->getId() );
        //$wx->debug = true;

        //网址接入时使用
        if (isset($_GET['echostr']))
        {
            $wx->valid();
        }

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
//        echo $wx->makeEvent();
        $wx->reply($reply);
    }

}
