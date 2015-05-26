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
//    维修进度通知
    private $processId = 'ubhal9RHjr99ubhsiOooCicbpsIHvnsff7vuAysMCWk';
//    待办任务提醒
    private $waitTaskId = 'Hie4EdBKBP4fQRt6o7Gl6QM-n415QLGVuKukb1R9e2s';

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
            'url'=>Url::toRoute(['m/taskdetail','id'=>$id],'http'),
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

        $this->sendTpl($tpl);
    }

    /*
     * 发送积分提醒
     * {{first.DATA}}

        {{FieldName.DATA}}:{{Account.DATA}}
        {{change.DATA}}积分:{{CreditChange.DATA}}
        积分余额:{{CreditTotal.DATA}}
        {{Remark.DATA}}
     *
     * $type = [1,2,3,4]  /  [打印赠送]
     */
    public function sendScore($openid,$url,$scoreChange,$scoreTotal,$type=1)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->scoreId,
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>'您的积分账户变更如下',
                    'color'=>'#000000',
                ],
                'FieldName'=>[
                    'value'=>'变动来源',
                    'color'=>'#000000',
                ],
                'Account'=>[
                    'value'=>'打印赠送',
                    'color'=>'#000000',
                ],
                'change'=>[
                    'value'=>$scoreChange>0 ? '增加':'减少',
                    'color'=>'#000000',
                ],
                'CreditChange'=>[
                    'value'=>$scoreChange,
                    'color'=>'#ff0000',
                ],
                'CreditTotal'=>[
                    'value'=>$scoreTotal,
                    'color'=>'#000000',
                ],
                'Remark'=>[
                    'value'=>'您可以在网站或手机APP使用积分下单抵现，100积分=1元。',
                    'color'=>'#173177',
                ],
            ]
        ];

        $this->sendTpl($tpl);
    }

    /*
     * 用户维修进度模板消息
     * {{first.DATA}}

服务类型：{{HandleType.DATA}}
处理状态：{{Status.DATA}}
提交时间：{{RowCreateDate.DATA}}
当前进度：{{LogType.DATA}}
{{remark.DATA}}
     */
    public function sendProcess($openid,$url,$process,$applyTime)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->processId,
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>'您好，您的维修申请有新的进度：',
                    'color'=>'#000000',
                ],
                'HandleType'=>[
                    'value'=>'机器维修',
                    'color'=>'#000000',
                ],
                'Status'=>[
                    'value'=>'已受理',
                    'color'=>'#000000',
                ],
                'RowCreateDate'=>[
                    'value'=> date('m月d日 H:i',$applyTime),
                    'color'=>'#000000',
                ],
                'LogType'=>[
                    'value'=>$process,
                    'color'=>'#ff0000',
                ],
                'Remark'=>[
                    'value'=>'点击“详情”查看详细处理结果，如有疑问请联系客服',
                    'color'=>'#173177',
                ],
            ]
        ];
        return $this->sendTpl($tpl);
    }
    /*
     * 待办事项提醒
     * 1、用户评价提醒
     * {{first.DATA}}
事项名称：{{keyword1.DATA}}
发起人：{{keyword2.DATA}}
发起时间：{{keyword3.DATA}}
{{remark.DATA}}
     */
    public function sendWaiting($openid,$url,$machineTime,$applyTime)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->waitTaskId,
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>'您申请的机器维修已于['.date('m月d日 H:i',$machineTime).']维修完成,去评价一下吧！',
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>'机器维修完成',
                    'color'=>'#000000',
                ],
                'keyword2'=>[
                    'value'=>'系统自动',
                    'color'=>'#000000',
                ],
                'keyword3'=>[
                    'value'=> date('m月d日 H:i',$applyTime),
                    'color'=>'#000000',
                ],

                'Remark'=>[
                    'value'=>'福利来了，评价赠送积分哦！',
                    'color'=>'#173177',
                ],
            ]
        ];
        return $this->sendTpl($tpl);
    }
    /*
     * 发送模板消息
     */
    private function sendTpl($tpl)
    {
        $curl = new Curl();
        $res = $curl->postJson($this->sendUrl,json_encode($tpl),['access_token'=>$this->accessToken()]);
        if( $res['errcode'] )
            Yii::$app->end(json_encode(['status'=>0,'msg'=>$res['errmsg']]));
        return true;
    }
} 