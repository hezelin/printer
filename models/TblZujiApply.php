<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_zuji_apply".
 *
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $company
 * @property string $info
 * @property string $create_at
 */
class TblZujiApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_zuji_apply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'company', 'info', 'create_at'], 'required'],
            [['info'], 'string'],
            [['create_at'], 'integer'],
            [['name', 'email', 'company'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'phone' => '手机',
            'email' => '邮箱',
            'company' => '公司',
            'info' => '备注',
            'create_at' => '提交时间',
        ];
    }
}
