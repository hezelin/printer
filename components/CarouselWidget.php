<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;
use app\assets\IscrollAsset;

class CarouselWidget extends Widget{
    /*
     * title,link,imgurl
     * carousel 配置
     */
    public $data = [];

    /*
     * 以下4个参数，默认为 id
     */
    public $wrap = 'carousel-wrap';
    public $carousel = 'carousel';
    public $iscroll = 'carousel-iscroll';
    public $nav = 'carousel-nav';
    public $align = 'right';
    public $backgroundColor = '#444';

    /*
     * 以下一个参数默认为 class
     */
    public $itemClass = 'slide';

    /*
     * 是否自动循环
     */
    public $autoRun = false;
    /*
     * 初始化 init 的变量
     */
    public $count;

    public function init(){
        parent::init();
    }

    public function run(){
        $this->registerScript();
        IscrollAsset::register( $this->getView() );
        return $this->renderHtml();
    }

    public function renderHtml()
    {
        $content = <<< MODEL_CONTENT
<div id="{$this->wrap}" style="background-color:{$this->backgroundColor};">
    <div id="{$this->carousel}">
        <div id="{$this->iscroll}">
              {$this->renderItem()}
        </div>
    </div>
</div>

<div id="{$this->nav}"><div id="dotty"></div></div>
MODEL_CONTENT;
        return $content;

    }

    /*
     * carousel 循环的图表信息
     * <div class="slide">
            <p class="carousel-title">标题</p>
            <a href="javascript:void(0)">
                <img src="/uploads/201504/1427946118_390.jpg" style="width: 100%;" />
            </a>
       </div>
        =====================================================
     * 标题 p 标签可省略， a 标签可省略
     */
    public function renderItem()
    {
        $items =[];
        foreach($this->data as $d){
            if(isset($d['imgurl']) && is_file($d['imgurl']) ){
                $p = Html::tag('p',$d['title'],['class'=>'carousel-title']);
                $a = (isset($d['link']) && $d['link'])? Html::a(Html::img('/'.$d['imgurl']),$d['link']):Html::img('/'.$d['imgurl']);
                $items[] = Html::tag('div',$p.$a,['class'=>$this->itemClass]);
            }else if( is_string($d) ){
                $img = Html::img($d);
                $items[] = Html::tag('div',$img,['class'=>$this->itemClass]);
            }
        }
        return implode("\n",$items);
    }
    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {
        // 名字取唯一值
        $jsName = 'carousel'.$this->getId();
        $autoRun = $this->autoRun? 'var nn=0;
        setInterval(function () {
            nn ++;
            if(nn == count){
                myScroll.goToPage(0,0,1000);
                nn =0;
            }else
                myScroll.next();
        }, 5000);':'';

        switch($this->align){
            case 'right': $left = 'document.body.clientWidth-count*24+4'; break;
            case 'center': $left = 'document.body.clientWidth/2-count*12+4'; break;
            default: $left = 4; break;
        }

        $script = <<<MODEL_JS
    var myScroll;
    function {$jsName} () {
        var count = document.getElementById("{$this->iscroll}").getElementsByTagName("img").length;
        for(i=0;i<count;i++){
            document.getElementById("{$this->iscroll}").querySelectorAll(".{$this->itemClass}").item(i).style.cssText = " width:"+document.body.clientWidth+"px;height:"+document.body.clientWidth/2+"px";
            //设置图片显示大小
            document.getElementById("{$this->iscroll}").getElementsByTagName("img").item(i).style.cssText = " width:"+document.body.clientWidth+"px";
            document.getElementById("{$this->iscroll}").getElementsByTagName("img").item(i).style.cssText = " height:"+document.body.clientWidth/2+"px";
        }
        document.getElementById("{$this->iscroll}").style.cssText = " width:"+document.body.clientWidth*count+"px";
        document.getElementById("{$this->carousel}").style.cssText = " width:"+document.body.clientWidth+"px;height:"+document.body.clientWidth/2+"px";
        document.getElementById('{$this->nav}').style.cssText = "width:"+(count*24-8)+"px;left:"+({$left})+"px;top:"+(document.body.clientWidth/2-32)+"px";
        myScroll = new IScroll('#{$this->carousel}', {
            scrollX: true,
            scrollY: false,
            momentum: false,
            snap: true,
            snapSpeed: 400,
            click:true,
            indicators: {
                el: document.getElementById('{$this->nav}'),
                resize: false
            }
        });
        {$autoRun}
    }
    document.addEventListener('DOMContentLoaded', {$jsName}, false);
MODEL_JS;
        $this->view->registerJs($script,\yii\web\View::POS_END);
    }
}
?>