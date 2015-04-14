<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/24
 * Time: 12:00
 */
namespace app\models;

use yii\db\ActiveRecord;

class Carousel extends ActiveRecord
{
    /**
     * @return string 返回该AR类关联的数据表名
     */
    public static function tableName()
    {
        return '{{%carousel}}';
    }
}