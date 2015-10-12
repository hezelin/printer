<?php
namespace app\components;

use app\assets\SwiperAsset;
use yii\base\Widget;
use yii\helpers\Html;
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
    public $linkClick;


    public function init(){
        parent::init();
    }

    public function run(){
        SwiperAsset::register( $this->getView() );
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
    <div id="scroller-passthrough" class="swiper-wrapper">
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
                $items[] = Html::a($d['name'],'javascript:void(0)',['key'=>$d['key'],'class'=>'swiper-slide has-click']);
            else
                $items[] = Html::a($d['name'],'javascript:void(0)',['key'=>$d['key'],'class'=>'swiper-slide']);
        }
        return implode("\n",$items);
    }
    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {
        $data = $this->linkClick? 'data-':'';
        $script = <<<MODEL_JS
    var myScroll{$this->getId()};
    var q='',key='';
    var startId ={$this->startId};
    var action = '{$this->action}';
    function getHtml(d){
        var html = [];

        html.push('<a class="item-list" {$data}href="detail?id='+d.wx_id+'&item_id='+d.id+'">');
        html.push('<img src="'+d.cover+'">');
        html.push('<span><h5>'+d.name+'</h5>');
        html.push('<p class="mtm_p"><b>￥'+d.price+'</b>'+d.category+'</p></span></a>');
        return html.join('');
    }
    function getData(action,key,startId2,q,type)
    {
        $.ajax({
                type:'get',
                url: action,
                data:{'key':key,'q':q,'startId':startId2,'format':'json'},
                dataType:'json',
                success:function(resp){
                    if(resp.status==1){
                        var d = resp.data;
                        startId = resp.startId;
                        var html = [];
                        for(var i in d){
                            html.push( getHtml(d[i]) );
                        }
                        if(type === 'append')
                            $('#item-list-wrap').append( html.join('') );
                        else
                            $('#item-list-wrap').html( html.join('') );

                        if( resp.len < 10 )
                             $('#item-more').addClass('item-more-end').text('没有数据了');
                         else
                             $('#item-more').removeClass('item-more-end').text('查看更多');
                    }else{
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                        if(type === 'html')
                            $('#item-list-wrap').html('');
                    }
                }
            });
    }

    $(function(){

        myScroll{$this->getId()} = new Swiper('#wrapper-passthrough',{
            pagination: false,
            paginationClickable: true,
            slidesPerView: 'auto'
        });

        // 点击类目
        $('#wrapper-passthrough').on('click','a',function(){
            $(this).addClass('has-click').siblings('a').removeClass('has-click');
            key = $(this).attr('key');
            getData(action,key,'','','html');
        });
        // 点击加载更多
        $('#item-more').click(function(){
            if( $(this).hasClass('item-more-end') ) return false;
            getData(action,key,startId,q,'append');
        });

        // 搜索按钮
        $('#s-open').click(function(){
            $('#search-input').show();
        });

        $('#s-close').click(function(){
            $('#search-show').hide();
            q='';
            getData(action,key,'',q,'html');
        });

        $('#search-input .s-button').click(function(){
            if( $('#search-input .s-input').val() ){
                q = $('#search-input .s-input').val();
                getData(action,key,'',q,'html');
                $('#search-show').show();
                $('#search-show .s-text').text(q);
            }
            $('#search-input').hide();
        });
        {$this->linkClick}
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
    padding:7px;
    margin:0;
    float:left;
    color:#444;
}
#wrapper-passthrough a:active,#wrapper-passthrough a:visited{ color:#444;}
#wrapper-passthrough .has-click:active,#wrapper-passthrough .has-click:visited{ color:#83cf53;}

#wrapper-passthrough .has-click{
    border-color: #83cf53;
    border-bottom:1px solid #83cf53;
    color:#83cf53;
    text-shadow: 2px 2px 4px #ffffff;
}

#wrapper-passthrough {
	position: fixed;
	z-index: 1;
	height: 45px;
	width: 100%;
	background: #ccc;
	overflow: hidden;
	-ms-touch-action: none;
	margin-bottom:2px;
	box-shadow: 0px 2px 2px #eee;

}
#scroller-passthrough {
	position: absolute;
	z-index: 1;
	height: 45px;
	border-bottom:1px solid #ccc;
}
MODEL_CSS;
        $this->view->registerCss($css);
    }
}
?>