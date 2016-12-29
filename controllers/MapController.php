<?php

namespace app\controllers;

use app\models\Cache;
use app\models\config\Tool;
use app\models\ToolBase;
use yii\helpers\Url;

class MapController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 租赁用户地图显示
     */
    public function actionRent()
    {
        $points = (new \yii\db\Query())
            ->select('t.id,t.name,t.address,t.latitude as lat,t.longitude as lng,t.machine_id,t.monthly_rent,black_white,colours,t.due_time,t.first_rent_time,t.rent_period,t.apply_word,t.status,
                m.brand_name,m.model_name,m.series_id,m.cover')
            ->from('tbl_rent_apply t')
            ->leftJoin('tbl_machine m','m.id=t.machine_id')
            ->where(['t.status'=>[2,3],'t.wx_id'=>Cache::getWid()])
            ->all();

        foreach($points  as &$m)
            if($m['lat'] && $m['lng'])
            {
                $data = ToolBase::bd_encrypt($m['lat'],$m['lng']);
                $m['lng'] = number_format($data['lon'],6,'.','');
                $m['lat'] = number_format($data['lat'],6,'.','');

                $m['html'] = '<div class="point-li" id="point-'.$m['id'].'" lng="'.$m['lng'].'" lat="'.$m['lat'].'">
                                <div class="point-row">
                                    <span class="point-name">'.$m['name'].'</span>
                                    <span class="point-address">'.$m['address'].'</span>
                                </div>
                                <div class="point-row">
                                    '.($m['monthly_rent']? '<span class="black">月租：'.$m['monthly_rent'].'元</span>':'').'
                                    '.($m['black_white']? '<span class="black">黑白：'.Tool::schemePrice($m['black_white']).'</span>':'').'
                                    '.($m['colours']? '<span class="colours">彩色：'.Tool::schemePrice($m['colours']).'</span>':'').'
                                </div>
                                <div class="point-row">
                                    <span class="brand-model">机型：'.$m['brand_name'].'/'.$m['model_name'].'</span>
                                    <span class="brand-model">机编：'.$m['id'].'</span>
                                    '.($m['series_id']? '<span class="colours">客编：'.$m['series_id'].'</span>':'').'
                                </div>
                                <div class="point-icon"></div>
                </div>';
                    
            }else
                unset($m);

        return $this->render('rent',['points'=>$points]);
    }

    /**
     *  维修人员分布地图
     *
     *  20161201 biao 添加维护人员分布地图
     *
     */
    public function actionMaintain(){

        //1. 读取维修人员表，获取经纬度
        //20161228 biao 维修员表：新增状态字段
        $points = (new \yii\db\Query())
            ->select(['name','longitude as lng','latitude as lat'])
            ->from('tbl_user_maintain')
            ->where(['wx_id' => Cache::getWid(), 'status' => 10])
            ->all();

        //2. 判断数据合法性
//        foreach ($points as &$m){
//            if($m['lat'] && $m['lng']){
//                $data = ToolBase::bd_encrypt($m['lat'],$m['lng']);
//
//                $m['lng'] = number_format($data['lon'],6,'.','');
//                $m['lat'] = number_format($data['lat'],6,'.','');
//
//            }else{
//                unset($m);
//            }
//        }

        return $this->render('maintain',['points' => $points]);
    }

}
