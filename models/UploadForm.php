<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/31
 * Time: 14:22
 */
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 *上传图片模型类
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'gif, jpg, jpeg, png, bmp',],
        ];
    }
}