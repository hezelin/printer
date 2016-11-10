<?php

namespace app\controllers;

use app\models\Cache;
use app\models\code\MachineAhead;
use app\models\TblMachineSearch;
use app\models\TblQrcodeSetting;
use app\models\ToolBase;
use Yii;
use app\models\WxCode;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class CodeController extends \yii\web\Controller
{
    public $layout = 'console';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
        $params = Yii::$app->request->queryParams;
        if( !isset($params['TblMachineSearch']['come_from']) )
            $params['TblMachineSearch']['come_from'] = 1;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 提前生成机器二维码
     */
    public function actionAhead()
    {
        $count = (new \yii\db\Query())
            ->select('count(*)')
            ->from('tbl_machine')
            ->where(['come_from'=>4])
            ->scalar();

        if($num = (int)Yii::$app->request->post('ahead-code')){

            if( ($num + $count) > 300 )
                Yii::$app->session->setFlash('error','已达到限制300条数据');
            else{
                $machine= new MachineAhead($num);
                $machine->createAll();
                $count += $num;
                $this->refresh();
            }
        }

        $searchModel = new TblMachineSearch();
        $params = Yii::$app->request->queryParams;
        $params['TblMachineSearch']['come_from'] = 4;

        $dataProvider = $searchModel->search($params);

        return $this->render('ahead', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
        ]);
    }
    /*
     * 批量打印机器码
     * post 提交多个机器编号,英文分号隔开
     */
    public function actionMachineall()
    {
        if(Yii::$app->request->post('list')){
            $ids = explode(',',Yii::$app->request->post('list'));
            sort($ids);

            $model = (new \yii\db\Query())
                ->select('series_id,id')
                ->from('tbl_machine')
                ->where(['id'=>$ids])
                ->all();


            foreach( $model as &$row){

                $index = (int)($row['id']/500);
                $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;

                if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$row['id'].'.png') ){

                    $url = Url::toRoute(['codeapi/machine','id'=>$row['id']],'http');
                    $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
                    $fileName = $dir.'/'.$row['id'].'.png';
                    shell_exec("qrencode -o $fileName '$url' -s 10 -m 2 -l H");
                }
                $row['qrcodeImgUrl'] = $imgUrl.'/'.$row['id'].'.png';
            }


            $setting = (new \yii\db\Query())
                ->select('*')
                ->from('tbl_qrcode_setting')
                ->where(['wx_id'=>Cache::getWid(),'enable'=>'Y'])
                ->one();

            $data = [
                'img'=> [
                    'width'=>' width="320px"',
                    'img'=>'/images/qrcode-bgimg-test.jpg',
                    'style'=>'',
                    'bgWidth'=>'340',
                ],
                'series'=>'',
                'code'=>'',
                'apply'=>'',
            ];

            if($setting){
                if($series = json_decode($setting['bg_img'],true) ){

                    $data['img']['style'] = isset($series['width'])? 'width:'.$series['width'].';':'100%;';

                    if( isset($series['width']) && $series['width'])
                    {
                        $data['img']['width'] = ' width="'.$series['width'].'"';
                        $data['img']['bgWidth'] = (int)$series['width'];
                    }

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
                if($series = json_decode($setting['apply'],true) ){
                    foreach($series as $k=>$v)
                        $data['apply'] .= "$k:$v;";
                }
                unset($setting);
            }
            return $this->render('machineall',['data'=>$data,'model'=>$model]);
        }
    }

    /*
     * 生成单个机器码,$id 位机器的id
     * 根据id 大小切割 文件夹
     * 图片文件不存在则下载
     */
    public function actionSetting($id)
    {
        set_time_limit(0);
        $model = (new \yii\db\Query())
            ->select('series_id,id')
            ->from('tbl_machine')
            ->where(['id'=>$id])
            ->one();

        $index = (int)($id/500);
        $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.png') ){

            $url = Url::toRoute(['codeapi/machine','id'=>$id],'http');
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            $fileName = $dir.'/'.$id.'.png';
            shell_exec("qrencode -o $fileName '$url' -s 10 -m 2 -l H");
        }

        $setting = (new \yii\db\Query())
            ->select('*')
            ->from('tbl_qrcode_setting')
            ->where(['wx_id'=>Cache::getWid(),'enable'=>'Y'])
            ->one();

        $data = [
            'series'=>'',
            'user'=>'',
            'code'=>'',
            'apply'=>'',
            'seriesNum'=>$model['series_id'],
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.png',
            'applyId' => $model['id'],
            'img'=> [
                'width'=>'',
                'img'=>'/images/qrcode-bgimg-test.jpg',
                'style'=>'width:320px',
                'bgWidth'=>'320'
            ],
            'seriesCss'=>[
                'top'=>138,
                'left'=>335,
                'color'=>'#444444',
                'font-size'=>'22',
            ],
            'applyCss'=>[
                'top'=>70,
                'left'=>60,
                'color'=>'#FF4500',
                'font-size'=>'45',
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

            if($series = json_decode($setting['apply'],true) ){
                foreach($series as $k=>$v){
                    $data['apply'] .= "$k:$v;";
                    $data['applyCss'][$k]= $k=='color'? $v:(int)$v;
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
        set_time_limit(0);
        $model = (new \yii\db\Query())
            ->select('series_id,id')
            ->from('tbl_machine')
            ->where(['id'=>$id])
            ->one();

        $index = (int)($id/500);
        $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.png') ){

            $url = Url::toRoute(['codeapi/machine','id'=>$id],'http');
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            $fileName = $dir.'/'.$id.'.png';
            shell_exec("qrencode -o $fileName '$url' -s 10 -m 2 -l H");
        }
        $setting = (new \yii\db\Query())
            ->select('*')
            ->from('tbl_qrcode_setting')
            ->where(['wx_id'=>Cache::getWid(),'enable'=>'Y'])
            ->one();

        $data = [
            'img'=> [
                'width'=>' width="320px"',
                'img'=>'/images/qrcode-bgimg-test.jpg',
                'style'=>'',
                'bgWidth'=>'340',
            ],
            'series'=>'',
            'user'=>'',
            'code'=>'',
            'apply'=>'',
            'seriesNum'=>$model['series_id'],
            'machineId'=>$id,
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.png'
        ];

        if($setting){
            if($series = json_decode($setting['bg_img'],true) ){

                $data['img']['style'] = isset($series['width'])? 'width:'.$series['width'].';':'100%;';

                if( isset($series['width']) && $series['width'])
                {
                    $data['img']['width'] = ' width="'.$series['width'].'"';
                    $data['img']['bgWidth'] = (int)$series['width'];
                }

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
            if($series = json_decode($setting['apply'],true) ){
                foreach($series as $k=>$v)
                    $data['apply'] .= "$k:$v;";
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
            $setting->apply = json_encode(Yii::$app->request->post('apply'));
            $setting->user_name = json_encode(Yii::$app->request->post('user'));
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

    /*
     * 生成公众号二维码
     */
    public function actionWeixin()
    {
        $wx_num = (new \yii\db\Query())
            ->select('wx_num')
            ->from('tbl_weixin')
            ->where(['id'=>Cache::getWid()])
            ->scalar();
        return $this->render('weixin',['wx_num'=>$wx_num]);
    }

    /*
     * 批量生成配件二维码
     *
     */
    public function actionParts()
    {
        if( Yii::$app->request->post('num') && Yii::$app->request->post('item_id') ) {
            $itemId = Yii::$app->request->post('item_id');
            $id = Cache::getWid();
            $imgUrl = [];
            $preDir = '/uploads/tmp/' . date('ymd', time()) . '/';
            for ($i = 0; $i < Yii::$app->request->post('num'); $i++) {
                $un = uniqid();
                $url = Url::toRoute(['/shop/codeapi/parts','id' =>$id,'item'=>$itemId,'un'=>$un],'http');
                $dir = ToolBase::newDir($preDir, Yii::getAlias('@webroot'));
                $fileName = $dir . '/' . $un . '.png';
                shell_exec("qrencode -o $fileName '$url' -s 4 -m 2 -l M");
                $imgUrl[] = $preDir . $un . '.png';
            }
            return $this->render('parts', ['imgUrl' => $imgUrl]);
        }

        return $this->render('itemSelect');
    }
}
