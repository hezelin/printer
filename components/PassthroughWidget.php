<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\assets\IscrollAsset;
use app\assets\ZeptoAsset;

class PassthroughWidget extends Widget{
    /*
     * [
     *  ['name'=>'',
     *   'active'=>'false',
     *   'key'=>'all'
     *  ]
     * ]
     */
    public $data = [];
    public $action;
    public $method = 'get';
    public $backgroundColor = '#fff';


    public function init(){
        parent::init();
    }

    public function run(){
        IscrollAsset::register( $this->getView() );
        ZeptoAsset::register( $this->getView() );
        $this->registerCss();
        $this->registerScript();
        return $this->renderHtml();
    }

    /*
     * <div id="wrapper-passthrough">
	        <div id="scroller-passthrough">
                <a>Pretty cell 1</a>
                <a>Pretty cell 2</a>
                <a>Pretty cell 3</a>
                <a>Pretty cell 4</a>
                <a>Pretty cell 5</a>
            </div>
        </div>
     */
    public function renderHtml()
    {
        $content = <<< MODEL_CONTENT
<div id="wrapper-passthrough" style="background-color:{$this->backgroundColor};">
    <div id="scroller-passthrough">
        {$this->renderItem()}
    </div>
</div>
MODEL_CONTENT;
        return $content;

    }

    public function renderItem()
    {
        $items =[];

        foreach($this->data as $d){
            if(isset($d['active']) && $d['active'])
                $items[] = Html::a($d['name'],'javascript:void(0)',['key'=>$d['key'],'class'=>'has-click']);
            else
                $items[] = Html::a($d['name'],'javascript:void(0)',['key'=>$d['key']]);
        }
        return implode("\n",$items);
    }
    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {

        $script = <<<MODEL_JS
    var myScroll{$this->getId()};
    var key,lastIndex;
    $(function(){

        myScroll{$this->getId()} = new IScroll('#wrapper-passthrough', {
            eventPassthrough: true,
            scrollX: true,
            scrollY: false,
            preventDefault: false
        });
        $('#wrapper-passthrough').on('click','a',function(){

            $(this).addClass('has-click').siblings('a').removeClass('has-click');
            var key = $(this).attr('key');
            /*$.getJSON('{$this->action}', {'key':key}, function(resp){
                alert('xxx');
            });*/
        });
    });
MODEL_JS;
        $this->view->registerJs($script,\yii\web\View::POS_END);
    }

    public function registerCss()
    {
        $css = <<< MODEL_CSS
#wrapper-passthrough a{
    display: inline;
    font-size: 16px;
    text-align: center;
    height:30px;
    line-height:30px;
    padding:8px 12px;
    margin:0;
    float:left;
    color:#444;
}
#wrapper-passthrough a:active,#wrapper-passthrough a:visited{ color:#444;}
#wrapper-passthrough .has-click:active,#wrapper-passthrough .has-click:visited{ color:#83cf53;}

#wrapper-passthrough .has-click{
    border-color: #83cf53;
    border-bottom:2px solid #83cf53;
    color:#83cf53;
    text-shadow: 2px 2px 4px #ffffff;
}

#wrapper-passthrough {
	position: relative;
	z-index: 1;
	height: 56px;
	width: 100%;
	background: #ccc;
	overflow: hidden;
	-ms-touch-action: none;

}
#scroller-passthrough {
	position: absolute;
	z-index: 1;
	height: 46px;
	width:500px;
	border-bottom:2px solid #ccc;
	background-image: -moz-linear-gradient(top, #ffffff,#e1e1e1);
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1,#e1e1e1));
	background-image: -o-linear-gradient(top,  #ffffff,#e1e1e1); 
}
MODEL_CSS;
        $this->view->registerCss($css);
    }
}
?>