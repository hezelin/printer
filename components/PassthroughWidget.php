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
     *   'active'=>'true',
     *   'key'=>'all'
     *  ]
     * ]
     */
    public $data = [];
    public $action;
    public $startId = 0;
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
    var q='',key='';
    var startId ={$this->startId};
    function getHtml(d){
        var html = [];

        html.push('<a class="item-list" href="detail?id='+d.wx_id+'&item_id='+d.id+'">');
        html.push('<img src="'+d.cover+'">');
        html.push('<span><h5>'+d.name+'</h5>');
        html.push('<p class="mtm_p"><b>￥'+d.price+'</b>'+d.category+'</p></span></a>');
        return html.join('');
    }
    $(function(){

        myScroll{$this->getId()} = new IScroll('#wrapper-passthrough', {
            eventPassthrough: true,
            scrollX: true,
            scrollY: false,
            preventDefault: false
        });

        // 点击类目
        $('#wrapper-passthrough').on('click','a',function(){

            $(this).addClass('has-click').siblings('a').removeClass('has-click');
            var key = $(this).attr('key');
            $.ajax({
                type:'get',
                url:'{$this->action}',
                data:{'key':key,'format':'json'},
                dataType:'json',
                success:function(resp){
                    if(resp.status==1){
                        var d = resp.data;
                        startId = resp.startId;
                        var html = [];
                        for(var i in d){
                            html.push( getHtml(d[i]) );
                        }
                        $('#item-list-wrap').html( html.join('') );
                        if( resp.len < 10 )
                             $('#item-more').addClass('item-more-end').text('没有数据了');
                        else
                             $('#item-more').removeClass('item-more-end').text('查看更多');

                    }else
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                }
            });
        });
        // 点击加载更多
        $('#item-more').click(function(){
            if( $(this).hasClass('item-more-end') ) return false;
            $.ajax({
                type:'get',
                url:'{$this->action}',
                data:{'key':key,'q':q,'startId':startId,'format':'json'},
                dataType:'json',
                success:function(resp){
                    if(resp.status==1){
                        var d = resp.data;
                        startId = resp.startId;

                        var html = [];
                        for(var i in d){
                            html.push( getHtml(d[i]) );
                        }
                        $('#item-list-wrap').append( html.join('') );
                        if( resp.len < 10 )
                             $('#item-more').addClass('item-more-end').text('没有数据了');
                         else
                             $('#item-more').removeClass('item-more-end').text('查看更多');
                    }else
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                }
            });
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
    line-height:30px;
    padding:7px 12px;
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
	height: 54px;
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
/*	background-image: -moz-linear-gradient(top, #ffffff,#e1e1e1);
	background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1,#e1e1e1));
	background-image: -o-linear-gradient(top,  #ffffff,#e1e1e1); */
}
MODEL_CSS;
        $this->view->registerCss($css);
    }
}
?>