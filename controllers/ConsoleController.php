<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\Cache;
use app\models\TblWeixin;
use yii\web\NotFoundHttpException;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 工作报表
     */
    public function actionView($id)
    {
        $model = TblWeixin::find()
            ->where(['id'=>$id,'enable'=>'Y'])
            ->asArray()
            ->one();

        if( $model == null )
            throw new NotFoundHttpException('查看的页面不存在');

        /*
         * 保存 微信id,24小时
         */
        Cache::setValue('u:'.Yii::$app->user->id.':wid', $model['id'],60*60*24);

        Yii::$app->session['wechat'] = $model;

        // 是否选择公众号，跳转
        if( Yii::$app->request->get('url'))
            return $this->redirect( Yii::$app->request->get('url') );
        return $this->render('view');
    }

    /*
     * 数据统计
     */
    public function actionAnalyze()
    {
        $item = (new \yii\db\Query())
            ->select('cost_price,sell_price,item_count,cate_count')
            ->from('tbl_analyze_product')
            ->where('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->orderBy('date_time desc')
            ->limit(1)
            ->one();
        $machine = (new \yii\db\Query())
            ->select('free_count,rent_count,scrap_count')
            ->from('tbl_analyze_machine')
            ->where('wx_id=:wid',[':wid'=>Cache::getWid()])
            ->orderBy('date_time desc')
            ->limit(1)
            ->one();

        $fault = new TblAnalyzeFault();

        return $this->render('analyze',['item'=>$item,'machine'=>$machine,'fault'=>$fault->getCharts()]);
    }
}
