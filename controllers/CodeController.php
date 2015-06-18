<?php

namespace app\controllers;

use app\models\Cache;
use app\models\MachineSearch;
use app\models\TblMachineSearch;
use app\models\TblQrcodeSetting;
use app\models\ToolBase;
use Yii;
use app\models\TblMachine;
use app\models\WxCode;
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
        $id = Cache::getWid();
        $wx = new WxCode($id);
        return $this->render('binding',[ 'qrcodeImgUrl'=>$wx->bindMaintainCode() ]);
    }

    /*
     * 生成机器码引导页面
     */
    public function actionIndex()
    {
        $searchModel = new TblMachineSearch();
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
     * 生成单个机器码,$id 位机器的id
     * 根据id 大小切割 文件夹
     * 图片文件不存在则下载
     */
    public function actionSetting($id)
    {
        $model = TblMachine::findOne($id);

        $index = (int)($id/500);
        $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.jpg') ){
            $urlParams = [
                'text' => Url::toRoute(['codeapi/machine','id'=>$id],'http'),
            ];
            $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);

            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            file_put_contents($dir.'/'.$id.'.jpg',file_get_contents($qrcodeImgUrl));
        }

        $setting = TblQrcodeSetting::findOne(['wx_id'=>Cache::getWid(),'enable'=>'Y']);

        $data = [
            'series'=>'',
            'code'=>'',
            'seriesNum'=>$model->series_id,
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.jpg',
            'img'=> [
                'width'=>'',
                'img'=>'/images/qrcode-bgimg-test.jpg',
                'style'=>'width:700px',
                'bgWidth'=>'700'
            ],
            'seriesCss'=>[
                'top'=>65,
                'left'=>50,
                'color'=>'#FF4500',
                'font-size'=>'55',
            ],
            'codeCss'=>[
                'top'=>22,
                'left'=>330,
                'width'=>120
            ],

        ];

        if($setting){
            if($series = json_decode($setting['bg_img'],true) ){
                $data['img']['style'] = isset($series['width'])? 'width:'.$series['width'].';':'100%;';
                $data['img']['width'] = isset($series['width'])? ' width="'.$series['width'].'"':'';
                $data['img']['bgWidth'] = isset($series['width'])? (int)$series['width']:700;
                $data['img']['img'] = isset($series['img'])? $series['img']:'';
            }

            if($series = json_decode($setting['series'],true) ){
                foreach($series as $k=>$v){
                    $data['series'] .= "$k:$v;";

                    $data['seriesCss'][$k]= $k=='color'? $v:(int)$v;
                }
            }
            if($series = json_decode($setting['code'],true) ){
                foreach($series as $k=>$v){
                    $data['code'] .= "$k:$v;";
                    $data['codeCss'][$k]= (int)$v;
                }
            }
            unset($setting);
        }

        unset($model);
        return $this->render('setting',['data'=>$data]);
    }

    /*
     * 生成单个机器码,$id 位机器的id
     * 根据id 大小切割 文件夹
     * 图片文件不存在则下载
     */
    public function actionMachine($id)
    {
        $model = TblMachine::findOne($id);

        $index = (int)($id/500);
        $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.jpg') ){
            $urlParams = [
                'text' => Url::toRoute(['codeapi/machine','id'=>$id],'http'),
            ];
            $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            file_put_contents($dir.'/'.$id.'.jpg',file_get_contents($qrcodeImgUrl));
        }
        $setting = TblQrcodeSetting::findOne(['wx_id'=>Cache::getWid(),'enable'=>'Y']);

        $data = [
            'img'=> [
                'width'=>'',
                'img'=>'/images/qrcode-bgimg-test.jpg',
                'style'=>'',
                'bgWidth'=>'',
            ],
            'series'=>'',
            'code'=>'',
            'seriesNum'=>$model->series_id,
            'machineId'=>$model->id,
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.jpg'
        ];

        if($setting){
            if($series = json_decode($setting['bg_img'],true) ){
                $data['img']['style'] = isset($series['width'])? 'width:'.$series['width'].';':'100%;';
                $data['img']['width'] = isset($series['width'])? ' width="'.$series['width'].'"':'';
                $data['img']['img'] = isset($series['img'])? $series['img']:'';
            }

            if($series = json_decode($setting['series'],true) ){
                foreach($series as $k=>$v)
                    $data['series'] .= "$k:$v;";
            }
            if($series = json_decode($setting['code'],true) ){
                foreach($series as $k=>$v)
                    $data['code'] .= "$k:$v;";
            }
            unset($setting);
        }
        unset($model);
        return $this->render('machine',['data'=>$data]);
    }

    /*
     * 机器二维码 配置保存
     */
    public function actionConfig()
    {
        if( Yii::$app->request->isAjax ){
            $setting = TblQrcodeSetting::findOne(['wx_id'=>Cache::getWid(),'enable'=>'Y']);
            if(!$setting){
                $setting = new TblQrcodeSetting();
                $setting->wx_id = Cache::getWid();
            }

            $setting->bg_img = json_encode(Yii::$app->request->post('bgImg'));
            $setting->series = json_encode(Yii::$app->request->post('series'));
            $setting->code = json_encode(Yii::$app->request->post('code'));
            $setting->add_time = time();
            if($setting->save())
                echo json_encode(['status'=>1]);
            else echo json_encode(['status'=>0,'msg'=>'保存数据失败，出现未知错误！'.ToolBase::arrayToString($setting->errors)]);
        }
    }

    /*
     * 生成积分二维码 url
     */
    public function actionScore()
    {
        $id = Cache::getWid();
        $wx = new WxCode($id);
        return $this->render('score',[ 'qrcodeImgUrl'=>$wx->scoreCode() ]);
    }

}
