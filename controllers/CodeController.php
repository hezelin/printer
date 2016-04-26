<?php

namespace app\controllers;

use app\models\Cache;
use app\models\ConfigBase;
use app\models\TblMachineSearch;
use app\models\TblQrcodeSetting;
use app\models\ToolBase;
use Yii;
use app\models\WxCode;
use yii\helpers\Url;
use yii\filters\VerbFilter;

class CodeController extends \yii\web\Controller
{
    public $layout = 'console';

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
        $params = Yii::$app->request->queryParams;
        if( !isset($params['TblMachineSearch']['status']) )
            $params['TblMachineSearch']['status'] = 1;

        $dataProvider = $searchModel->search($params);

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
//        $this->layout = 'blank';
        $list = [];
        if(Yii::$app->request->post('list')){

            $data = explode(',',Yii::$app->request->post('list'));

            foreach( $data as $i => $row){
                list($list[$i]['machineId'] , $list[$i]['seriesNum']) = explode('|',$row);

                $index = (int)($list[$i]['machineId']/500);
                $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;

                if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$list[$i]['machineId'].'.png') ){

                    $url = Url::toRoute(['codeapi/machine','id'=>$list[$i]['machineId']],'http');
                    $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
                    $fileName = $dir.'/'.$list[$i]['machineId'].'.png';
                    shell_exec("qrencode -o $fileName '$url' -s 10 -m 2 -l H");
                }
                $list[$i]['qrcodeImgUrl'] = $imgUrl.'/'.$list[$i]['machineId'].'.png';
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
                'list'=>$list,
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
            unset($list);
            return $this->render('machineall',['data'=>$data]);
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
            ->select('t.name,p.series_id')
            ->from('tbl_rent_apply t')
            ->leftJoin('tbl_machine p','p.id=t.machine_id')
            ->where('t.machine_id=:mid and t.enable="Y"',[':mid'=>$id])
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
            'seriesNum'=>$model['series_id'],
            'userName' => $model['name'],
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.png',
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
            'userCss'=>[
                'top'=>100,
                'left'=>50,
                'color'=>'#FF4500',
                'font-size'=>'40',
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

            if($series = json_decode($setting['user_name'],true) ){
                foreach($series as $k=>$v){
                    $data['user'] .= "$k:$v;";

                    $data['userCss'][$k]= $k=='color'? $v:(int)$v;
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
            ->select('t.series_id,a.name,a.id,t.come_from')
            ->from('tbl_machine t')
            ->leftJoin('tbl_rent_apply a','a.machine_id=t.id')
            ->where(['t.id'=>$id])
            ->one();

       /* echo '<pre>';
        print_r($model);
        exit;*/
        $index = (int)($id/500);
        $imgUrl = '/images/qrcode/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.png') ){

            $url = Url::toRoute(['codeapi/machine','id'=>$id],'http');
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            $fileName = $dir.'/'.$id.'.png';
            shell_exec("qrencode -o $fileName '$url' -s 10 -m 2 -l H");
        }
        /*$setting = (new \yii\db\Query())
            ->select('*')
            ->from('tbl_qrcode_setting')
            ->where(['wx_id'=>Cache::getWid(),'enable'=>'Y'])
            ->one();*/

        $setting = [];
        $data = [
            'img'=> [
                'width'=>' width="700px"',
                'img'=>'/images/qrcode-bgimg-test.jpg',
                'style'=>'',
                'bgWidth'=>'',
            ],
            'series'=>'',
            'user'=>'',
            'code'=>'',
            'apply'=>'',
            'come_from'=> ConfigBase::machineComeFrom($model['come_from']),
            'seriesNum'=>$model['series_id'],
            'userName' => $model['name'],
            'applyId' => $model['id'],
            'machineId'=>$id,
            'qrcodeImgUrl'=>$imgUrl.'/'.$id.'.png'
        ];

        if($setting){
            if($series = json_decode($setting['bg_img'],true) ){

                $data['img']['style'] = isset($series['width'])? 'width:'.$series['width'].';':'100%;';

                if( isset($series['width']) && $series['width'])
                    $data['img']['width'] = ' width="'.$series['width'].'"';

                $data['img']['img'] = isset($series['img'])? $series['img']:'';
            }

            if($series = json_decode($setting['series'],true) ){
                foreach($series as $k=>$v)
                    $data['series'] .= "$k:$v;";
            }
            if($series = json_decode($setting['user_name'],true) ){
                foreach($series as $k=>$v)
                    $data['user'] .= "$k:$v;";
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
     * 配件二维码
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
