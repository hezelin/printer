<?php
namespace app\modules\shop\models;

use app\models\TblCategory;
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
} 