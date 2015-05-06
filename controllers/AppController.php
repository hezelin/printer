<?php

namespace app\controllers;
use app\models\TblUserMaintain;
use app\models\TblUserWechat;
use app\models\ToolBase;
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

        /*
         * 二维码扫描处理,关注事件扫描二维码
         */
        if( ($wx->msg->Event == 'SCAN' && $key = $wx->msg->EventKey) ||
            ($wx->msg->Event == 'subscribe' && substr($wx->msg->EventKey,0,8) == 'qrscene_' && $key = substr($wx->msg->EventKey,8,-1))
        ){
            if($key == 1)       // 绑定维修员事件
            {
                $maintain = TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$wx->msg->FromUserName]);
                if($maintain)
                    return $wx->makeText( date('Y-m-d H:i',$maintain->add_time).'已绑定为维修员，无需再绑定！');
                else{
                    $maintain = new TblUserMaintain();
                    $maintain->wx_id = $id;
                    $maintain->openid = (string)$wx->msg->FromUserName;
                    $maintain->add_time = time();
                    if($maintain->save())
                        return $wx->makeText('成功绑定为维修员！');
                    return $wx->makeText( ToolBase::arrayToString($maintain->errors));
                }
            }
        }


//        $wx->reply($reply);
    }

    /*
     * 扫描事件处理
     * $key == 1,绑定维修员
     */
    private function scan($key){

    }

    public function actionCreateMenu($id)
    {
        $weixin = new WxBase($id);
        $weixin->delUser('oXMyut8n0CaEuXxxKv2mkelk_uaY');

    }

}
