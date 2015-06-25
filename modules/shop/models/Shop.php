<?php
namespace app\modules\shop\models;

use yii\helpers\ArrayHelper;
use app\models\Cache;
/**
 * 商城状态类
 */

class Shop {

    /*
     * 获取类目
     */
    public static function getCategory($id='',$wx_id='')
    {
        $category = TblCategory::findAll(['wx_id'=>$wx_id? :Cache::getWid()]);
        $category = $category? ArrayHelper::map($category,'id','name'):[];
        return $id? $category[$id]:$category;
    }

    /*
     * 获取passthroughWidget  类目
     */
    public static function getMenu($wx_id='')
    {
        $category = TblCategory::findAll(['wx_id'=>$wx_id? :Cache::getWid()]);
        $tmp = [
            ['name'=>'全部','key'=>'all','active'=>true]
        ];
        if($category){
            foreach($category as $c){
                $tmp[]=[
                    'name'=>$c['name'],
                    'key'=>$c['id']
                ];
            }
        }
        return $tmp;
    }

    /*
     * 获取 携带配件状态
     */
    public static function getBringParts($id='')
    {
        $data = [1=>'申请中',2=>'携带中',3=>'已分配',4=>'已归还'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }
    /*
     * 获取维修配件状态
     */
    public static function getFaultParts($id='')
    {
        $data = [1=>'申请中',2=>'已发送',3=>'已到达',4=>'已归还'];
        if($id)
            return isset($data[$id])? $data[$id]:'出错';
        return $data;
    }
} 