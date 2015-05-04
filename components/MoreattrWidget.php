<?php
/*
 * 更多属性部件
 */
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class MoreattrWidget extends Widget{

    /*
     * 部件的可设置属性
     */
    public $data = [];
    public $modelId = '#Moreattr-model';
    public $formId = '#my-form';
    public $targetId = '#add';
    public $headName = '补充属性';
    public $saveButtonName = '保存属性';

    public function init(){
        parent::init();
        $this->formId = $this->formId . $this->getId();
    }

    public function run(){
        $this->registerScript();
        return $this->renderHtml();
    }

    /*
     * 输出 html
     */
    public function renderHtml()
    {
        $modelName = substr($this->modelId,1);
        $formName = substr($this->formId,1);
        $content = <<< MODEL_CONTENT
<div id="{$modelName}" class="fade modal" role="dialog" tabindex="-1">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
{$this->headName}
</div>
<div class="modal-body">
<form id="{$formName}" class="form-inline">
{$this->renderItem()}
</form>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" class="btn btn-default add-row">增加一列</button>
        <button id="{$modelName}-save" type="button" class="btn btn-primary">{$this->saveButtonName}</button>
</div>
</div>
</div>
</div>
MODEL_CONTENT;
        return $content;

    }

    /*
     * 根据 json 格式数据，还原为数组
     * 输出多列数据
     */
    public function renderItem()
    {
        $html = [];
        if($this->data){
            foreach( json_decode($this->data,true) as $row)
            {
                $html[] = Html::tag('div',
                    Html::textInput('attr[]',$row['name'],['class'=>'form-control','placeholder'=>'属性名']).
                    '&nbsp;&nbsp;'.
                    Html::textInput('attr[]',$row['value'],['class'=>'form-control','placeholder'=>'属性值']).
                    '&nbsp;&nbsp;'.
                    Html::button('<i class="glyphicon glyphicon-trash"></i>',['class'=>'btn btn-danger del-row']),
                    ['class'=>'form-group m-b-5']
                );
            }
            return implode("\n",$html);
        }
        return '<div class="form-group m-b-5">
<input type="text" class="form-control" name="attr[]" value="" placeholder="属性名">
&nbsp;&nbsp;
<input type="text" class="form-control" name="attr[]" value="" placeholder="属性值">&nbsp;&nbsp;
<button type="button" class="btn btn-danger del-row"><i class="glyphicon glyphicon-trash"></i> </button>
</div>';
    }
    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {
        $script = <<<MODEL_JS
var tpl = '<div class="form-group m-b-5"><input type="text" class="form-control" name="attr[]" value="" placeholder="属性名"> &nbsp;&nbsp; <input type="text" class="form-control" name="attr[]" value="" placeholder="属性值">&nbsp;&nbsp; <button type="button" class="btn btn-danger del-row"><i class="glyphicon glyphicon-trash"></i> </button> </div>';

    $('{$this->modelId} .add-row').click(function(){
        $('{$this->formId}').append(tpl);
    });

    $('{$this->modelId}').on('click','.del-row',function(){
        $(this).closest('.form-group').remove();
    });

    $('{$this->modelId}-save').click(function(){
        var tmp = [];
        var i=0;
        var data = {};
        $("{$this->modelId} input[name='attr[]']").each(function(){
            if( i%2 == 0 ){
                data.name = $.trim($(this).val());
            }
            else{
                data.value= $.trim($(this).val());
                if(data.value !== '' && data.name !== ''){
                    tmp.push(data );
                    data = {};
                }
            }
            i++;
        });
        $('{$this->targetId}').val(JSON.stringify(tmp) );
        $('{$this->modelId}').modal('hide');
    });

    $('{$this->targetId}').click(function(){
        $('{$this->modelId}').modal('show');
    });
MODEL_JS;
        $this->view->registerJs($script,\yii\web\View::POS_READY);
    }
}
?>