<?php
/**
 * Created by harry
 * 维修模板消息
 */

namespace app\models;
use app\models\common\Debug;
use app\models\config\Tool;
use app\models\Curl;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

class WxTemplate extends WxBase {

//    https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN
    private $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send';

    /*
     * 获取模板id
     * $type = newTask,score,dueTime,checkInfo,process,waitTask,cancel
     */
    public function getTmpId($type)
    {
        $tmpId = (new \yii\db\Query())
            ->select($type)
            ->from('tbl_weixin_template')
            ->where('wx_id=:wid',[':wid'=>$this->id])
            ->scalar();
        if(!$tmpId)
            throw new BadRequestHttpException('模板id');
        return $tmpId;
    }
    /*
     * 发送维修员任务提醒
     * 张师傅您好，现有新的维修任务！
        故障原因：坏晒鼓
        客户地址：广州市xx路yy街zz号
        客户信息：陈小姐，13566881122
        申请时间：2015年9月18日 09:10
        如有疑问跟总部联系
     */
    public function sendTask($id,$openid,$name,$reason,$address,$info,$applyTime,$remark='')
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('newTask'),
            'url'=>Url::toRoute(['/maintain/task/detail','id'=>$id],'http'),
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
                    'value'=>$remark? '留言：'.$remark:'没有留言！',
                    'color'=>'#f07f12',
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
     * $type = [1,2,3,4,5,6,7]  /  [打印赠送]
     */
    public function sendScore($openid,$url,$scoreChange,$scoreTotal,$type=1)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('score'),
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
                    'value'=>ConfigBase::$scoreFromStatus[$type],
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
                    'value'=>'积分可以兑换大奖和购物抵现。',
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
            'template_id'=>$this->getTmpId('process'),
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

    {{first.DATA}}
待办事项：{{keyword1.DATA}}
备注说明：{{keyword2.DATA}}
{{remark.DATA}}
     */
    public function sendWaiting($openid,$url,$machineTime,$applyTime)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('waitTask'),
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
                    'value'=> '时间'.date('m月d日 H:i',$applyTime),
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
     * 服务取消通知
     * 1、用户取消维修，给用户、维修员提醒
     * {{first.DATA}}
服务项目：{{keyword1.DATA}}
服务时间：{{keyword2.DATA}}
{{remark.DATA}}
     */
    public function sendCancelService($openid,$url,$name,$reason,$operaTime,$applyTime)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('cancel'),
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>'您好，'.$name.'已于（'.date('Y年m月d日 H:i',$operaTime).'）取消维修',
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>'机器维修',
                    'color'=>'#000000',
                ],
                'keyword2'=>[
                    'value'=>date('Y年m月d日 H:i',$applyTime),
                    'color'=>'#000000',
                ],
                'remark'=>[
                    'value'=>'取消原因：'.$reason.'。如有疑问请跟客服联系',
                    'color'=>'#ff0000',
                ],
            ]
        ];
        return $this->sendTpl($tpl);
    }

    /*
     * 会员资料审核提醒
     * {{first.DATA}}
        审核结果：{{keyword1.DATA}}
        原因：{{keyword2.DATA}}
        {{remark.DATA}}
     */
    public function sendCheck($openid,$url,$first,$key1,$key2,$remark)
    {
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('checkInfo'),
            'url'=>$url,
            'data'=> [
                'first'=>[
                    'value'=>$first,
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>$key1,
                    'color'=>'#000000',
                ],
                'keyword2'=>[
                    'value'=>$key2,
                    'color'=>'#000000',
                ],
                'remark'=>[
                    'value'=>$remark,
                    'color'=>'#ff0000',
                ],
            ]
        ];
        return $this->sendTpl($tpl);
    }

    /**
     * 20161129 商家发送通知
     *
     *{{first.DATA}}
    内容：{{keyword1.DATA}}
    时间：{{keyword2.DATA}}
    {{remark.DATA}}
     *
     */
    public function sendNotify($openid, $first, $key1, $key2, $remark){
        $tpl = [
            'touser'=>$openid,
            'template_id'=>$this->getTmpId('updateNotify'),
            'url'=>'',
            'data'=> [
                'first'=>[
                    'value'=>$first,
                    'color'=>'#000000',
                ],
                'keyword1'=>[
                    'value'=>$key1,
                    'color'=>'#000000',
                ],
                'keyword2'=>[
                    'value'=>$key2,
                    'color'=>'#000000',
                ],
                'remark'=>[
                    'value'=>$remark,
                    'color'=>'#f07f12',
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
        if($tpl['touser'] && strlen($tpl['touser']) == 28){
            $curl = new Curl();
            $res = $curl->postJson($this->sendUrl,json_encode($tpl),['access_token'=>$this->accessToken()]);
            if( $res['errcode'] )
//                Yii::$app->end(json_encode(['status'=>0,'msg'=>$res['errmsg']]));
                return false;
            return true;
        }
        return false;
    }

    /*
     * 设置模板所属行业
     * 开通 2个行业
     */
    public function setWechatTmp()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry';
        $curl = new Curl();
        $tmpIds = [
            'industry_id1'=>1,
            'industry_id2'=>40
        ];
        $res = $curl->postJson($url,json_encode($tmpIds),['access_token'=>$this->accessToken()]);
//        if( $res['errcode'] )
//            throw new BadRequestHttpException('设置模板行业失败：'.$res['errmsg']);
    }

    /*
     * 获取 微信模板id
     */
    public function setWechatTmpId()
    {
        //test
        $data = [
            'newTask'=>'OPENTM204588400',
            'score'=>'TM00230',
            'dueTime'=>'TM00008',
            'checkInfo'=>'OPENTM201057607',
            'process'=>'TM00254',
//            'waitTask'=>'OPENTM200706571',
            'waitTask'=>'OPENTM406438955',
            'cancel'=>'OPENTM203353498',
            'updateNotify' => 'OPENTM405766411',//20161129 新增的更新通知消息模块
        ];
        $url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template';
        $curl = new Curl();

        $tmp = $error = [];
        foreach($data as $k=>$type){
            $params = [
                'template_id_short'=>$type,
            ];
            $res = $curl->postJson($url,json_encode($params),['access_token'=>$this->accessToken()]);
            if( !$res['errcode'] )
                $tmp[$k] = $res['template_id'];
            else
                $error[] = $res;
        }
//        if(count($tmp) == count($data)){
            $model = TblWeixinTemplate::findOne($this->id);
            if(!$model){
                $model = new TblWeixinTemplate();
                $model->wx_id = $this->id;
            }
            foreach($tmp as $k=>$v)
                $model->$k = $v;
            if(!$model->save()){
                print_r($model->errors);
                exit;
            }
//                throw new BadRequestHttpException('添加模板id,入库失败');
//        }

//        throw new BadRequestHttpException('添加模板id失败或者模板id已存在');
    }

    public function getAllTemplate()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template';
        $curl = new Curl();
        $data = $curl->postJson($url,['access_token'=>$this->accessToken()]);
        return $data;
    }
} 