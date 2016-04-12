<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_test".
 *
 * @property integer $id
 * @property string $content
 */
class Test extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['content'], 'safe'],
            [['content'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容',
        ];
    }
}
