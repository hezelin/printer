<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblUserCount;
use app\models\TblUserScoreLog;
use app\models\TblUserScoreLogSearch;
use app\models\TblUserWechat;
use app\models\WxTemplate;
use yii\helpers\Url;
use Yii;
use yii\web\BadRequestHttpException;

class AdminscoreController extends \yii\web\Controller
{
    public $layout = 'console';
    public function actionApi()
    {
        $id = Cache::getWid();
        $openid = Cache::getValue('score:' . $id);
        if(!$openid)
            Yii::$app->end( json_encode(['status'=>0]) );

        if( !$model = TblUserWechat::find()->with('count')->where(['wx_id'=>$id,'openid'=>$openid])->one() )
            Yii::$app->end( json_encode(['status'=>0]) );


        $res['status'] = 1;
        $res['data'] = '<p> <img src="'.substr($model['headimgurl'],0,-1).'46" />&nbsp;&nbsp;&nbsp;
    '.$model['nickname'].' , '.$this->getSex($model['sex']).' , '.$model['country'].$model['province'].$model['city'].'
    &nbsp;&nbsp;<span class="small">'.date('Y-m-d H:i',$model['subscribe_time']).'关注</span></p><p>用户积分：'.$model['count']['score'].'</p>';

        echo json_encode($res);
    }

    public function actionSend()
    {
        $id = Cache::getWid();

        if( Yii::$app->request->get('openid') ){
            Cache::setValue('score:'.$id,Yii::$app->request->get('openid'),60*30);
            $this->redirect(Url::toRoute('send'));
        }
        $openid = Cache::getValue('score:'.$id);

        if( Yii::$app->request->post('score') ) {

            $score = Yii::$app->request->post('score');
            $model = new TblUserScoreLog();
            $model->wx_id = $id;
            $model->openid = $openid;
            $model->score = $score;
            $model->add_time = time();

            if ($model->save()) {
                $sql = "insert into tbl_user_count (`wx_id`,`openid`,`score`) values ($id,'$openid',$score)
    ON DUPLICATE KEY UPDATE `score`=`score`+ values(`score`);";

                $row = Yii::$app->db->createCommand($sql)->execute();
                if (!$row)
                    throw new BadRequestHttpException('系统错误!');
                $scoreTotal = TblUserCount::find()->select('score')->where(['openid' => $openid, 'wx_id' => $id])->scalar();
                Cache::delValue('score:' . $id);              // 删除等级获取积分的 openid

                $wechat = new WxTemplate($id);
                $wechat->sendScore($openid, '/adminscore/log', $score, $scoreTotal);

                return $this->render('//tips/success', [
                    'tips' => '成功为用户赠送 ' . $score . ' 积分, 用户目前总积分（' . $scoreTotal . '）',
                    'btnText' => '返回赠送积分',
                    'btnUrl' => Url::toRoute(['send'])
                ]);
            }
        }

        $model = $openid? TblUserWechat::find()->with('count')->where(['wx_id'=>$id,'openid'=>$openid])->one():NULL;
        $isScan = $openid? 1:0;
        return $this->render('send',['model'=>$model,'isScan'=>$isScan]);
    }

    public function actionLog()
    {
        $searchModel = new TblUserScoreLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('log',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    private function getSex($sex)
    {
        return $sex==1? '男':($sex==2? '女':'未知');
    }

}
