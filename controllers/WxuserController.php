<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblUserMaintainSearch;
use app\models\TblUserWechatSearch;
use app\models\WxUser;
use Yii;

class WxuserController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['batch-update', 'show-rst'],
                'rules' => [
                    [
                        'actions' => ['batch-update','show-rst'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    public $layout = 'console';
    public function actionList()
    {
        $searchModel = new TblUserWechatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list',['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
    }

    public function actionSelect()
    {
        $searchModel = new TblUserWechatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fromUrl = $this->dealUrl( Yii::$app->request->get('url') );

        return $this->render('select',['dataProvider'=>$dataProvider,'searchModel' => $searchModel,'fromUrl'=>$fromUrl]);
    }

    public function actionSelectMaintain()
    {
        $searchModel = new TblUserMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fromUrl = $this->dealUrl( Yii::$app->request->get('url') );

        return $this->render('select-maintain',['dataProvider'=>$dataProvider,'searchModel' => $searchModel,'fromUrl'=>$fromUrl]);
    }

    /*
      * 公众号id,用户 openid
      */
    public function actionUpdate($wx_id,$openid)
    {
        $weixin = new WxUser($wx_id);
        if($weixin->updateUser($openid))
            return $this->render('//tips/success', [
                'tips' => '更新成功！',
                'btnText' => '返回列表',
                'btnUrl' => \yii\helpers\Url::toRoute(['/wxuser/list'])
            ]);
        else
            return $this->render('//tips/error', [
                'tips' => '修改保存失败！',
                'btnText' => '重试',
                'btnUrl' => \yii\helpers\Url::toRoute(['/wxuser/list'])
            ]);
        //return $this->redirect(['list']);
    }


    /*
     * 批量更新用户信息
     * 20170104 biao
     */
    public function actionBatchUpdate()
    {
        $wx = new WxUser(Cache::getWid());
        if($wx->pullUser())
            return 'success';
    }

    /*
     * 处理批量更新的结果显示
     * 20170104 biao
     *
     */
    public function actionShowRst($rst)
    {
        if($rst == 'success')
            return $this->render('//tips/success',[
                'tips' => '更新成功！',
                'btnText' => '返回',
                'btnUrl' => \yii\helpers\Url::toRoute(['/wxuser/list'])
            ]);
        else
            return $this->render('//tips/error', [
                'tips' => '更新失败！ 【可能原因】: 超出每天更新限制的次数',
                'btnText' => '返回',
                'btnUrl' => \yii\helpers\Url::toRoute(['/wxuser/list'])
            ]);

    }

    /*
     * 拉取用户
     */
    public function actionPull()
    {
        $wxId = Cache::getWid();
        $wx = new WxUser($wxId);
        $wx->pullUser();
    }

    /*
     * 处理 url
     */
    private function dealUrl($url)
    {
        if(strpos($url,'?') !== false){                     // 如果路径存在 问号 ？
            $arr = [];

            list($baseUrl,$query) = explode('?',urldecode($url) );
            if( $query ){
                foreach( explode('&',$query) as $q )
                {
                    if(!$q) continue;                       // 空白过滤
                    list($k,$v) = explode('=',$q);
                    if( $k == 'openid' )
                        continue;
                    $arr[$k] = $v;
                }
            }
            return $baseUrl . ($arr? '?'.http_build_query($arr).'&':'?');
        }

        return $url.'?';                                    //  没有问号 直接返回参数
    }

}
