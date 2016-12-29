<?php

namespace app\controllers;
use app\models\Cache;
use app\models\common\Debug;
use app\models\config\Tool;
use app\models\TblUserMaintain;
use app\models\TblWexinReply;
use app\models\tool\Coord;
use app\models\tool\Coordinate;
use app\models\ToolBase;
use app\models\WxUser;
use app\models\WxChat;
use Yii;
use yii\db\Query;
use yii\helpers\Url;

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

        /*$msgType = empty($wx->msg->MsgType) ? '' : strtolower($wx->msg->MsgType);

        switch ($msgType)
        {
            case 'text':
                //你要处理文本消息代码
                break;
            case 'image':
                //你要处理图文消息代码
                break;
            case 'voice':
                //你要处理音频消息代码
                break;
            case 'video':
                //你要处理视频消息代码
                break;
            case 'shortvideo':
                //你要处理小视频消息代码
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
        }*/

        /*
         * 变量转换
         */
        $wxEvent = (string)$wx->msg->Event;
        $wxEventKey = (string)$wx->msg->EventKey;
        $wxFromUser = (string)$wx->msg->FromUserName;
        /*
         * 用户关注，保存资料
         */
        if($wxEvent == 'subscribe'){
            $weixin = new WxUser($id);
            $weixin->getUser($wxFromUser,true);

            //[20161205 biao 增加：用户第一次关注，发送消息功能
            //return $wx->makeText("亲，你终于来了！");

            //20161205]

            //[20161214 增加数据库等
            $tbl_wexin_reply = TblWexinReply::find()->where(['wx_id' => $id])->one();
            if($tbl_wexin_reply->subscribe_reply){
                return $wx->makeText($tbl_wexin_reply->subscribe_reply);
            }else{
                return $wx->makeText("亲，你终于来了！");//默认设置
            }


            //20161214]
        }
        else if($wxEvent == 'unsubscribe'){
            $weixin = new WxUser($id);
            $weixin->delUser($wxFromUser);
        }

        /*
         * 二维码扫描处理,关注事件扫描二维码
         */
        if( ($wxEvent == 'SCAN' && $key = $wxEventKey) ||
            ($wxEvent == 'subscribe' && substr($wxEventKey,0,8) == 'qrscene_' && $key = substr($wxEventKey,8,-1))
        ){

            if($key == 1)               // 绑定维修员事件
            {
                $maintain = TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$wxFromUser]);
                if($maintain) {
                    //[20161228 biao 新增状态字段
                    //return $wx->makeText(date('Y-m-d H:i', $maintain->add_time) . '已绑定为维修员，无需再绑定！');

                    if($maintain->status == 10) {
                        return $wx->makeText(date('Y-m-d H:i', $maintain->add_time) . '已绑定为维修员，无需再绑定！');
                    }else{
                        $maintain->status = 10;//把已经解绑的维修员，重新绑定
                        if($maintain->save())
                            return $wx->makeText('成功绑定为维修员！');
                        return $wx->makeText(ToolBase::arrayToString($maintain->errors));
                    }
                    //20161228]
                }else{
                    $maintain = new TblUserMaintain();
                    $maintain->wx_id = $id;
                    $maintain->name = (new \yii\db\Query())->select('nickname')->from('tbl_user_wechat')->where(['wx_id'=>$id,'openid'=>$wxFromUser])->scalar();
                    $maintain->name || $maintain->name = '未知';
                    $maintain->openid = $wxFromUser;
                    $maintain->add_time = time();
                    if($maintain->save())
                        return $wx->makeText('成功绑定为维修员！');
                    return $wx->makeText(ToolBase::arrayToString($maintain->errors));
                }
            }elseif($key == 2 )          // 扫描积分二维码
            {
                Yii::$app->cache->set('score:'.$id,$wxFromUser,60*30);
                return $wx->makeText( '等待获得积分中...');
            }
        }

        if($wxEvent == 'LOCATION')
        {
            Tool::location($wxFromUser,$id,$wx->msg->Longitude,$wx->msg->Latitude,'wgs84');
            return 'success';
        }

//        $wx->reply($reply);
    }

    /*
     * 扫描事件处理
     * $key == 1,绑定维修员
     */
    /*private function scan($key){

    }*/

    public function actionCreateMenu($id)
    {
        $weixin = new WxBase($id);
        $weixin->delUser('oXMyut8n0CaEuXxxKv2mkelk_uaY');

    }

}
