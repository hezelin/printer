<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use Yii;

/*
 * 等待 loading 部件
 * 支持 调用前方法、关闭前方法
 */
class LoadingWidget extends Widget{

    /*
     * method 为 get or post, default get;
     */
    public $type = 'post';
    public $target = '.lin-loading';
    public $dataType = 'json';
    public $loading = '/css/icon/waitting.gif';
    public $th = 'th';

    public $url;
    public $beforeSend = 'function(XHR){}';
    public $success = 'function(reply){}';
    public $wid;
    //OS增加
    public $confirmMessage = '';
    public $childTarget = '';

    public function init(){
        parent::init();
        $this->wid = $this->getId();
    }

    public function run(){
        $this->registerScript();
        return $this->renderItems();
    }


    public function renderItems()
    {
        $content = <<< MODAL_CONTENT
        <div class="modal-dialog modal-loading">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center"><img src="{$this->loading}" /></div>
                </div>
            </div>
        </div>
MODAL_CONTENT;
        return Html::tag('div',$content,['id'=>$this->wid,'class'=>'fade modal','role'=>'dialog','tabindex'=>'-1']);
    }


    /*
     *  初始化 tree menu 高度
     */
    private  function registerScript()
    {
        $this->url = $this->url? "'" . $this->url . "'" : "$(this).attr('href')";
        $this->childTarget = $this->childTarget? "'" . $this->childTarget . "'," : ""; //可作用于动态添加的元素
        $confirm1 = $this->confirmMessage? "if(confirm('".$this->confirmMessage."')){":"";
        $confirm2 = $this->confirmMessage? "}":"";

        $script = <<<JS_TREE
        $('{$this->target}').on('click',{$this->childTarget}function(){
            if( $(this).hasClass('my-disabled') || $(this).hasClass('disabled') )
                return false;
            {$confirm1}
                var {$this->th} = $(this);
                $('#{$this->wid}').modal('show');
                $.ajax({
                    type:'{$this->type}',
                    url:{$this->url},
                    dataType:'{$this->dataType}',
                    beforeSend:{$this->beforeSend},
                    success:{$this->success},
                    complete:function(XHR, TS){
                        $('#{$this->wid}').modal('hide');
                    }
                });
            {$confirm2}

            return false;
        });
JS_TREE;
        $this->view->registerJs($script,\yii\web\View::POS_READY);
    }
}
?>