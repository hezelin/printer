<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_service_process".
 *
 * @property string $id
 * @property string $service_id
 * @property string $content
 * @property integer $process
 * @property string $add_time
 */
class TblServiceProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_service_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_id', 'content', 'add_time'], 'required'],
            [['service_id', 'process', 'add_time'], 'integer'],
            [['content'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_id' => 'Service ID',
            'content' => 'Content',
            'process' => 'Process',
            'add_time' => 'Add Time',
        ];
    }
}
