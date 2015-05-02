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
<div id="{$this->wrap}">
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
     */
    public function renderItem()
    {
        $items =[];
        foreach($this->data as $d){
            if( !is_file($d['imgurl']) )
                continue;
            $p = Html::tag('p',$d['title'],['class'=>'carousel-title']);
            $a = Html::a(Html::img('/'.$d['imgurl']),$d['link']? : 'javascript:void(0)');
            $items[] = Html::tag('div',$p.$a,['class'=>$this->itemClass]);
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
        document.getElementById('{$this->nav}').style.cssText = "width:"+(count*30-10)+"px;top:"+(document.body.clientWidth/2-35)+"px";
        myScroll = new IScroll('#{$this->carousel}', {
            scrollX: true,
            scrollY: false,
            momentum: false,
            snap: true,
            snapSpeed: 400,
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