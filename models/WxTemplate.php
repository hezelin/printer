<?php
/**
 * Created by harry
 * 维修模板消息
 */

namespace app\models;
use Yii;
use yii\helpers\Url;

class WxTemplate extends WxBase {

//    维修员新任务提醒
    private  $newTaskId = 'Ty7MIhYrwdcSqNv751xITR1iuv90kWUam6A5Z-pFw_c';
//    积分变动通知
    private  $scoreId = 'JNQ8qK-bkS1gcKYKnfdeHUkTFVNgMg6cFbs14fVMoIQ';
//    到期提醒通知
    private  $dueTimeId = 'VSYOyq5Sb9vO1MIJ1OT5wtBJjM8miNpXzU6vGG5T7C8';
//    资料审核通知
    private  $checkInfoId = 'annBT_1RFl0KWSsyj2Y_6KAjdvL48FGT2UebP84u4pI';

//    https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN
    private $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send';
    /*
     * 发送维修员任务提醒
     * 张师傅您好，现有新的维修任务！
        故障原因：坏晒鼓
        客户地址：广州市xx路yy街zz号
        客户信息：陈小姐，13566881122
        申请时间：2015年9月18日 09:10
        如有疑问跟总部联系
     */
    public function sendTask($id,$openid,$name,$reason,$address,$info,$applyTime)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->newTaskId,
            'url'=>Url::toRoute(['m/task','id'=>$id],'http'),
//            'topcolor'=>'#FF0000',
            'data'=> [
                'first'=>[
                    'value'=>$name.'您好，现有新的维修任务',
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>$reason,
                    'color'=>'#FF0000',
                ],
                'keyword2'=>[
                    'value'=>$address,
                    'color'=>'#000000',
                ],
                'keyword3'=>[
                    'value'=>$info,
                    'color'=>'#000000',
                ],
                'keyword4'=>[
                    'value'=>date('m月d日 H:i',$applyTime),
                    'color'=>'#000000',
                ],
                'remark'=>[
                    'value'=>'如有疑问跟总部联系!',
                    'color'=>'#173177',
                ],
            ]
        ];
        $curl = new Curl();
        $res = $curl->postJson($this->sendUrl,json_encode($tpl),['access_token'=>$this->accessToken()]);
        if( $res['errcode'] )
            Yii::$app->end(json_encode(['status'=>0,'msg'=>$res['errmsg']]));
        return true;
    }

} 