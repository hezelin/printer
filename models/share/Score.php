<?php

namespace app\models\share;

use app\models\TblUserCount;
use app\models\TblUserScoreLog;
use app\models\WxTemplate;
use yii\helpers\Url;
use Yii;

class Score
{
    // 微信公众号 id
    public $id;
    public $openid;
    public $type;   // 方式，1,2,3,4,5,6,7
    public $score;

    /*
     * 1 => '到店消费',
     * 2 => '在线消费',
     * 3 => '分享公众号链接',
     * 4 => '邀请租机链接',
     * 5 => '分享耗材链接',
     *
     * 6 => '邀请租机提成',
     * 7 => '分享耗材提成'
     */
    public function __construct($id,$openid,$type,$score)
    {
        $this->id = (int)$id;
        $this->openid = $openid;
        $this->type = (int)$type;
        $this->score = (int)$score;
    }

    /*
     * 积分变动
     * 1、tbl_user_score_log ，写入记录 （ 3/4/5 需要判断当天是否已获得积分）
     * 2、消息模板推送
     * 3、tbl_user_count ,   统计用户总积分
     */
    public function change()
    {

        if( in_array($this->type,[3,4,5]) ){
            $s = strtotime( date('Y-m-d',time()) );
            $hasShare = (new \yii\db\Query())
                ->select('count(*)')
                ->from('tbl_user_score_log')
                ->where(['wx_id'=>$this->id,'openid'=>$this->openid,'type'=>$this->type])
                ->andWhere(['between','add_time',$s,time()])
                ->scalar();
            if($hasShare) return false;
        }

        $model = new TblUserScoreLog();
        $model->wx_id = $this->id;
        $model->openid = $this->openid;
        $model->score = $this->score;
        $model->type = $this->type;
        $model->add_time = time();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->save();                     // 保存记录

            $model = TblUserCount::findOne(['wx_id'=>$this->id,'openid'=>$this->openid]);
            if(!$model){
                $model = new TblUserCount();
                $model->wx_id = $this->id;
                $model->openid = $this->openid;
                $model->score = 0;
            }
            $model->score += $this->score;
            $model->save();                     // 保存总积分

            $wx = new WxTemplate($this->id);    // 推送模板消息
            $wx->sendScore($this->openid, Url::toRoute(['/shop/i/score','id'=>$this->id],'http'),$this->score,$model->score,$this->type);

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            return false;
        }

        return true;
    }
}
