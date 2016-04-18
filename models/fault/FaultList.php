<?php

namespace app\models\fault;

use app\models\WxBase;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class FaultList
{
    // 微信公众号 id
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /*
     * 获取维修任务进程中记录
     */
    public function task()
    {
        $openid = WxBase::openId($this->id);
        $model = (new \yii\db\Query())
            ->select('t.id, t.content as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid,'t.enable' => 'Y']);

        if(Yii::$app->request->get('type') == 'evaluate')
            $model = $model->andWhere(['t.status'=>8]);
        elseif( Yii::$app->request->get('type') == 'history')
            $model = $model->andWhere(['t.status'=>9]);
        else
            $model = $model->andWhere(['<','t.status',8]);

        $model = $model->orderBy('t.add_time desc')
            ->all();

        foreach ($model as $i=>$m) {
            $content = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $content['cover'][0];
        }

        return $model;

        /*$tmp = [];
        foreach($model as $m){
            if($m['status'] == 8){
                $tmp['evaluate'][] = $m;
            }else
                $tmp['process'][] = $m;
        }
        unset($model);
        return $tmp;*/
    }

    /*
     * 获取历史维修完成记录
     */
    public function record()
    {
        $openid = WxBase::openId($this->id);
        $model = (new \yii\db\Query())
            ->select('t.id, t.content as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid,'t.status'=>9])
            ->andWhere(['t.enable' => 'Y'])
            ->orderBy('t.id desc')
            ->all();

        foreach ($model as $i=>$m) {
            $content = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $content['cover'][0];
        }
        return $model;
    }

    /*
     * 获取维修进度
     * $from = 1/2, 1为用户入口，2为维修员入口
     */
    public function progress($fault_id,$from = 1)
    {
        $model = (new \yii\db\Query())
            ->select('weixin_id as wx_id,status, id as fault_id,add_time')
            ->from('tbl_machine_service')
            ->where(['id' => $fault_id])
            ->one();

        $model['process'] = (new \yii\db\Query())
            ->select('content,add_time,process')
            ->from('tbl_service_process')
            ->where(['service_id' => $fault_id])
            ->orderBy('id desc')
            ->all();

        $model['processImg'] = [];                      // 放大预览
        foreach($model['process'] as &$p){
            if($p['process'] == 5){
                $tmp = '';
                $c = json_decode($p['content'],true);
                $tmp .= $c['text'];                     // 故障图片
                if($c['cover'] && count($c['cover']) >0){
                    $tmp .= '<div class="process-img">';
                    foreach($c['cover'] as $img){
                        $tmp .= '<img src="'.$img.'" />';
                        $model['processImg'][] = Yii::$app->request->hostInfo.$img;
                    }
                    $tmp .= '</div>';
                }

                if(isset($c['voice'],$c['voiceLen']) && $c['voice']){
                    $tmp .= '<div class="voice-row"><div id="voice-wrap2" data-value="3" data-time="'.$c['voiceLen'].'">
                            <div class="voice-image voice-playing"></div>
                            <p class="voice-time"><span id="voice-time2">'.$c['voiceLen'].'</span>＂</p>
                        </div></div>
                        <audio hidden="true" preload="auto" onended="play_ended2()" id="myaudio2">
                            <source src="'.$c['voice'].'" type="audio/mpeg">
                            "不支持播放录音"
                        </audio>';
                }

                $p['content'] = $tmp;
            }
        }

        $model['btn'] = '';

        if( $from == 1 ){
            if($model['status'] == 8)
                $model['btn'] = Html::a('评价维修',Url::toRoute(['s/evaluate','id'=>$this->id,'fault_id'=>$model['fault_id']]),[
                    'class'=>'h-fixed-bottom'
                ]);
            if($model['status']== 1 || $model['status'] == 2)
                $model['btn'] = Html::a('取消维修',Url::toRoute(['s/cancel','id'=>$this->id,'fid'=>$model['fault_id']]),[
                    'class'=>'h-fixed-bottom'
                ]);
        }

        return $model;
    }


    /*
     * 获取维修进度
     * $from = 1/2, 1为用户入口，2为维修员入口
     */
    public function progressWithHead($fault_id,$from = 1)
    {
        $model = (new \yii\db\Query())
            ->select('t.id as fault_id,t.content as fault_cover,t.desc,t.type as fault_type,t.remark,t.add_time,t.status,m.id,p.cover,
                    b.name as brand,p.type,m.series_id,m.wx_id
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','p.id=m.model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
            ->where(['t.id' => $fault_id])
            ->one();

        // 图片预览 路径设置
        $content = json_decode($model['fault_cover'],true);
        $model['fault_cover'] = Yii::$app->request->hostInfo.$content['cover'][0];
        foreach($content['cover'] as $cover)
            $model['cover_images'][] = Yii::$app->request->hostinfo.$cover;
        if( isset($content['voice'])){
            $model['voice_url'] = $content['voice'];
            $model['voice_len'] = $content['voiceLen'];
        }

        $model['process'] = (new \yii\db\Query())
            ->select('content,add_time,process')
            ->from('tbl_service_process')
            ->where(['service_id' => $fault_id])
            ->orderBy('id desc')
            ->all();

        $model['processImg'] = [];
        foreach($model['process'] as &$p){
            if($p['process'] == 5){
                $tmp = '';
                $c = json_decode($p['content'],true);
                $tmp .= $c['text'];
                if($c['cover'] && count($c['cover']) >0){
                    $tmp .= '<div class="process-img">';
                    foreach($c['cover'] as $img){
                        $tmp .= '<img src="'.$img.'" />';
                        $model['processImg'][] = Yii::$app->request->hostInfo.$img;
                    }
                    $tmp .= '</div>';
                }
                $p['content'] = $tmp;
            }
        }

        $model['btn'] = '';

        if( $from == 1 ){
            if($model['status'] == 8)
                $model['btn'] = Html::a('评价维修',Url::toRoute(['s/evaluate','id'=>$this->id,'fault_id'=>$model['fault_id']]),[
                    'class'=>'h-fixed-bottom'
                ]);
            if($model['status']== 1 || $model['status'] == 2)
                $model['btn'] = Html::a('取消维修',Url::toRoute(['s/cancel','id'=>$this->id,'fid'=>$model['fault_id']]),[
                    'class'=>'h-fixed-bottom'
                ]);
        }

        return $model;
    }
}
