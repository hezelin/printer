<?php

namespace app\controllers;

use app\models\TblUserMaintainSearch;
use app\models\TblUserWechatSearch;
use app\models\WxUser;
use Yii;

class WxuserController extends \yii\web\Controller
{
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

    public function actionSelectmaintain()
    {
        $searchModel = new TblUserMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $fromUrl = $this->dealUrl( Yii::$app->request->get('url') );

        return $this->render('selectmaintain',['dataProvider'=>$dataProvider,'searchModel' => $searchModel,'fromUrl'=>$fromUrl]);
    }

    /*
     * 公众号id,用户 openid
     */
    public function actionUpdate($wx_id,$openid)
    {
        $weixin = new WxUser($wx_id);
        $weixin->updateUser($openid);
        return $this->redirect(['list']);
    }

    /*
     * 拉取用户
     */
    public function actionPull()
    {
        $wx = new WxUser(1);
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
