<?php
/*
 * city,province 等依赖
 * @author harry
 * @time 2015年12月5日
 */
namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\View;
use yii\widgets\InputWidget;

class ItemDependentWidget extends InputWidget
{
    public $depend;                             // 依赖字段
    public $placeholder = '选择';                // 默认提示
    public $disabled = true;                    // 默认是否有效
    public $dataUrl;                            // dataUrl;
    public $dataType = 'html';                  // json / html（未完成）

    private $inputId;

    public function init()
    {
        parent::init();
        if( !$this->dataUrl )
            throw new BadRequestHttpException('依赖部件配置出错！');
        $this->inputId = Html::getInputId($this->model, $this->attribute);
    }

    public function run()
    {
        $this->registerClientScript();
        return Html::activeDropDownList($this->model, $this->attribute, [''=>$this->placeholder],['id' => $this->inputId,'disabled'=>$this->disabled,'class'=>'form-control']);
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        $dependId =  Html::getInputId($this->model,$this->depend);
        $addJs = '';
        if(  $this->model[$this->depend] && $this->model[$this->attribute] ){
            $addJs =<<< ADD_JS
$.get(
    '{$this->dataUrl}?id={$this->model[$this->depend]}',
    function(data){
        $('#{$this->inputId}').html(data).removeAttr('disabled').val('{$this->model[$this->attribute]}');
    }
);
ADD_JS;
        }
        $script =<<<MODEL_JS
        $('#{$dependId}').change(function(){
            var id = $(this).val();
            if( id == '' ){
                $('#{$this->inputId}').html('<option value="">选择</option>');
                return false;
            }
            $.get(
                '{$this->dataUrl}?id='+id,
                function(data){
                    $('#{$this->inputId}').html(data).removeAttr('disabled');
                }
            );
        });
        {$addJs}
MODEL_JS;

        $this->view->registerJs($script, View::POS_READY);
    }

    /*
     * 返回drownlist 内容
     */
    public function getLIst()
    {
        if( $this->model->{$this->depend} ){            // 依赖表单有选中

        }
    }
}