<?php

namespace app\controllers;

use app\models\MachineSearch;
use Yii;
use app\models\TblMachine;
use app\models\WxBase;
use yii\helpers\Url;
use yii\filters\VerbFilter;

class CodeController extends \yii\web\Controller
{
    public $layout = 'console';


    /*
     * 获取第三方二维码  url
     */
    private $qrcodeApiUrl = 'http://qr.liantu.com/api.php?';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'machineall' => ['post'],
                ],
            ],
        ];
    }

    /*
     * 维修员绑定码
     */
    public function actionBinding()
    {
        $id = Yii::$app->session['wechat']['id'];
        $wx = new WxBase($id);
        return $this->render('binding',[ 'qrcodeImgUrl'=>$wx->bindMaintainCode() ]);
    }

    /*
     * 生成机器码引导页面
     */
    public function actionIndex()
    {
        $searchModel = new MachineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 批量打印机器码
     * post 提交多个机器id|编号，英文分号隔开
     * 2|No1,3|1,6|35,7|L6,8|L7,9|88,10|89,11|10,12|11
     */
    public function actionMachineall()
    {
        $this->layout = 'blank';
        if(Yii::$app->request->post('list')){

            $data = explode(',',Yii::$app->request->post('list'));
            foreach( $data as $index => $row){
                list($list[$index]['id'] , $list[$index]['serial']) = explode('|',$row);
                $urlParams = [ 'text' => Url::toRoute(['codeapi/machine','id'=>$list[$index]['id'] ],'http'), ];
                $list[$index]['url'] = $this->qrcodeApiUrl . http_build_query($urlParams);
            }
            return $this->render('machineall',['list'=>$list]);
        }
    }

    /*
     * 生成单个机器码
     */
    public function actionMachine($id)
    {
        $model = TblMachine::findOne($id);
        $urlParams = [
            'text' => Url::toRoute(['codeapi/machine','id'=>$id],'http'),
        ];

        $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);

        return $this->render('machine',['model'=>$model,'qrcodeImgUrl'=>$qrcodeImgUrl]);
    }


    /*
     * 生成积分二维码 url
     */
    public function actionScore()
    {
        $id = Yii::$app->session['wechat']['id'];
        $urlParams = [
            'text' => Url::toRoute(['codeapi/score','id'=>$id],'http'),
        ];

        $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);

        return $this->render('score',[ 'qrcodeImgUrl'=>$qrcodeImgUrl ]);
    }

}
