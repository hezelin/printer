<?php
namespace app\modules\shop\models;

use app\models\TblCategory;
use yii\helpers\ArrayHelper;
use app\models\Cache;
/**
 * 商城状态类
 */

class Shop {

    public static function getCategory($id='')
    {
        $category = TblCategory::findAll(['wx_id'=>Cache::getWid()]);
        $category = $category? ArrayHelper::map($category,'id','name'):[];
        return $id? $category[$id]:$category;
    }
} 