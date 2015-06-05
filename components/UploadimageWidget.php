<?php
/*
 * ajax upload image
 * @author harry
 * @time 2015年6月5日
 */
namespace app\components;

use Yii;
use app\assets\UploadimageAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

class UploadimageWidget extends InputWidget
{
    /*
     * 上传图片的数量，默认为1个
     */
    public $imageLimit = 1;

    /*
     * 图片上传服务器 url
     */
    public $serverUrl;
    /*
     * 表单对应的id
     */
    private  $inputId;
    private $hasUploadImages = 0;

    public function init()
    {
        $this->inputId = Html::getInputId($this->model, $this->attribute);          // 查看源码，activeForm 生成规则
        if($this->model[$this->attribute]) {
            if( $this->imageLimit == 1 )
                $this->hasUploadImages = 1;
            else{
                $srcs = json_decode($this->model[$this->attribute], true);
                $this->hasUploadImages = count($srcs);
            }
        }
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();
        $this->registerCss();
        if ($this->hasModel()) {
            return Html::activeTextInput($this->model, $this->attribute, ['id' => $this->inputId,'class'=>'hidden']).$this->renderHtml();
        } else {
            return Html::textInput($this->id, $this->value, ['id' => $this->inputId,'class'=>'hidden']);
        }
    }

    /*
     * <div id="image-ajaxupload">上传图片</div>
     */
    public function renderHtml()
    {
        $params = $this->hasUploadImages == $this->imageLimit? ['id'=>'image-upload-'.$this->id, 'class'=>'upload-image-btn','style'=>'display: none;']:['id'=>'image-upload-'.$this->id, 'class'=>'upload-image-btn'];
        $value = '';
        if($this->model[$this->attribute]){
            if($this->imageLimit == 1){
                $value = '<div class="upload-image-btn"><img src="'.$this->model[$this->attribute].'"/><i class="delete-img glyphicon glyphicon-remove-circle"></i></div>';
            }
            else{
                $srcs = json_decode($this->model[$this->attribute],true);
                foreach($srcs as $src)
                    $value .= '<div class="upload-image-btn"><img src="'.$src.'"/><i class="delete-img glyphicon glyphicon-remove-circle"></i></div>';
            }

        }
        return Html::tag('div',$value,['id'=>'image-show-'.$this->id]).Html::tag('div','上传图片',$params);
    }

    /*
     * 注册 css 样式
     */
    public function registerCss()
    {
        $css = <<< MODEL_CSS
.upload-image-btn{
        border: 1px dashed #cccccc;
        width: 82px; height: 82px;
        line-height: 80px;
        text-align: center;
        color: #999999;
        vert-align: middle;
        float: left;
        margin-right: 15px;
        position: relative;
        margin-bottom: 5px;
    }
    .upload-image-btn img{
        max-width: 80px;
        max-height: 80px;;
    }
    .upload-image-btn .delete-img{
        position: absolute;
        right: -10px;
        top: -5px;
        width: 24px;
        height: 24px;
        color: #FF0000;
        cursor: pointer;
        font-size: 16px;
    }
MODEL_CSS;

        $this->view->registerCss($css);
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        UploadimageAsset::register($this->view);
        $script =<<<MODEL_JS
var uploadImage = $('#image-upload-{$this->id}');
var uploadImageWrap = $('#image-show-{$this->id}');
var uploadImageNum = {$this->imageLimit};
var hasUploadImage = {$this->hasUploadImages};
var uploadDisable = 0;
function resetImageVal(){
    var imgs = [];
    $('#image-show-{$this->id} .upload-image-btn img').each(function(){
        imgs.push( $(this).attr('src') );
    });
    if(imgs.length > 0)
        $('#{$this->inputId}').val(  uploadImageNum ==1? imgs[0]:JSON.stringify(imgs));
    else
        $('#{$this->inputId}').val('');

    hasUploadImage = imgs.length;
     if(hasUploadImage >= uploadImageNum){
        Ajax.disable();
        uploadDisable = 1;
        uploadImage.hide();
     }else{
        if( uploadDisable == 1){
            Ajax.enable();
            uploadDisable = 0;
            uploadImage.show();
        }
     }

}


var Ajax = new AjaxUpload('#image-upload-{$this->id}', {
    action: '{$this->serverUrl}',
    name: 'uploadfile',
    onSubmit: function(file, ext){
        if(hasUploadImage >= uploadImageNum){
            alert('只能上传' + uploadImageNum + '张图片');
            return false;
        }
        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
            alert('请上传图片文件');
            return false;
        }
        uploadImage.text('上传中...');
    },
    responseType:'json',
    onComplete: function(file, obj){
        uploadImage.text('上传图片');
        if(obj.status == 1){
            uploadImageWrap.append('<div class="upload-image-btn"><img src="'+obj.url+'"/><i class="delete-img glyphicon glyphicon-remove-circle"></i></div>');
            resetImageVal();
        }
        else alert( obj.msg);
    }
});

resetImageVal();
$('#image-show-{$this->id}').on('click','.delete-img',function(){
    $(this).closest('.upload-image-btn').remove();
    resetImageVal();
});
MODEL_JS;

        $this->view->registerJs($script, View::POS_READY);
    }
}