<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\assets\ZeptoAsset;

class FixedmenuWidget extends Widget{

    /*
     * 类目，url,name
     */
    public $menu = [];

    public function init(){
        parent::init();
    }

    public function run(){
        if( !$this->menu ) return false;
        $this->registerScript();
        ZeptoAsset::register( $this->getView() );
        return $this->renderHtml();
    }

    public function renderHtml()
    {
        $menu = '';
        foreach( $this->menu as $m){
            $menu .= Html::tag('li',Html::a($m['name'],$m['url']),['class'=>'themeStyle out']);
        }
        $content = <<< MODEL_CONTENT
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0)">
    <nav>
        <ul id="top_menu" class="top_menu">
            <input type="checkbox" id="plug-btn" class="plug-menu themeStyle">
            {$menu}
        </ul>
    </nav>
</div>
<div id="plug-wrap" style="display: none;" ></div>
MODEL_CONTENT;
        return $content;

    }

    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {
        $script = <<<MODEL_JS
$(function(){
    $(".plug-menu").click(function(){
        var li = $(this).parents('ul').find('li');
        if(li.attr("class") == "themeStyle on"){
            li.removeClass("themeStyle on");
            li.addClass("themeStyle out");
        }else{
            li.removeClass("themeStyle out");
            li.addClass("themeStyle on");
        }
    });
});
MODEL_JS;
        $this->view->registerJs($script,\yii\web\View::POS_END);
    }
}
?>