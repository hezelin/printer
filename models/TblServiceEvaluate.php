<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_service_evaluate".
 *
 * @property string $id
 * @property string $fault_id
 * @property integer $quality
 * @property integer $speed
 * @property integer $attitude
 * @property string $add_time
 */
class TblServiceEvaluate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_service_evaluate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fault_id', 'quality', 'speed', 'attitude', 'add_time'], 'required'],
            [['fault_id', 'quality', 'speed', 'attitude', 'add_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fault_id' => 'Fault ID',
            'quality' => 'Quality',
            'speed' => 'Speed',
            'attitude' => 'Attitude',
            'add_time' => 'Add Time',
        ];
    }
}
