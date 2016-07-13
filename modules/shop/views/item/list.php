<?php
use yii\helpers\Url;
$this->title = '商品列表';
use app\assets\HomeAsset;
HomeAsset::register($this);

$this->registerCssFile('/css/swiper/swiper.min.css',['depends'=>['app\assets\AuicssAsset']]);
$this->registerCssFile('/css/font-icon/im2/font-icon.css',['depends'=>['app\assets\AuicssAsset']]);
$this->registerCssFile('/css/aui/css/aui-pull-refresh.css',['depends'=>['app\assets\AuicssAsset']]);
?>

<style type="text/css">
    .rhome{
        font-family:'微软雅黑'  !important;
        max-width:640px;
        min-width:320px;
        margin:0 auto;
        background: #f8f8f8;
    }
    img{
        vertical-align:bottom;
    }

    /*swiper*/
    .swiper-pagination-bullet{
        border:2px solid #fff;
        background: none;
        opacity: 1;
    }
    .swiper-pagination-bullet-active{
        background: #fff;
    }
    .swiper-pagination-bullet{
        width:12px;
        height:12px;
    }

    /*main*/
    .mall_main .search{
        margin:15px 0;
        overflow:hidden;
        overflow:hidden;

    }
    .search input{
        outline: none;
        border:0 none;
    }
    .search .search_input{
        border:1px solid #d2d2d2;
        border-radius:4px;
        color:#999;
        padding-right: 50px;
        margin:0;
    }
    .search .icon-btn{
        width:45px;
        line-height: 45px;
        height:43px;
        margin-left:-50px;
        position:
            relative;
        z-index: 10;
        font-size: 25px;
        cursor: pointer;
    }
    .chat .chat_title{
        margin:0 0 15px 0;
        position: relative;
        color:#787878;
    }

    .chat .chat_title span:before{
        content:'';
        position: absolute;
        left:10px;
        top:12px;
        width:32%;
        height:1px;
        background: #d7d7d7;
    }
    .chat .chat_title span:after{
        content:'';
        position: absolute;
        right:10px;
        top:12px;
        width:32%;
        height:1px;
        background: #d7d7d7;
    }


    /*产品展示*/
    .chat_cp{
        font-size: 14px;
        margin-bottom: 10px;
        color:#000;
    }
    .chat_cp .cp_title{
        margin:5px 0;
    }
    .chat_cp .text_content{
        border:1px solid #eee;
        overflow:hidden;
        background:#fff;
    }

    .chat_cp .price{
        height:25px;
        line-height: 25px;
    }

    /*页尾*/
    .wrap{
        position: fixed;
        width:100%;
        bottom:0;
        max-width: 640px;
        min-width: 320px;
        background: #fff
    }
    .wrap a{
        color:#999;
    }
    .wrap .active-color{
        color:#33b5e5;
    }
    .footer_line{
        height:50px;
        overflow:hidden;
        width:100%;
    }
    .aui-load-container{
        height:auto;
    }
    .re-circle-loading{
        vertical-align: bottom;
        padding-top:3px;
    }
    .aui-loading-title{
        padding-top:3px;
    }

    /*bug*/
    .it_support{
        margin-top:-65px;
        margin-bottom: 15px !important;
    }
    .footer_line{
        height:42px !important;
    }
</style>
<div class="aui-load-container">
    <div class="aui-load-wrap">
        <div class="re-circle aui-text-center"></div>
        <div class="re-circle-loading aui-text-center"><img src="/images/loading-4.gif" width="30"></div>
        <div class="aui-loading-title"></div>
    </div>
    <div class="header_wechat">
        <div id="flash_rhome" class="aui-mg-b-15">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <a href="#"><img src="/images/flash2.png" width="100%"></a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#"><img src="/images/flash2.png" width="100%"></a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#"><img src="/images/flash2.png" width="100%"></a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#"><img src="/images/flash2.png" width="100%"></a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#"><img src="/images/flash2.png" width="100%"></a>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    <div class="mall_main aui-padded-0-10" style="overflow:hidden;">
        <div class="search aui-clearfix aui-padded-0-10">
            <input type="text" class="search_input aui-pull-left" placeholder="请输入关键字">
            <div class="aui-pull-left aui-text-center icon-btn">
                <span class="icon-uniE906" style="color:#787878;"></span>
            </div>
        </div>
        <div class="chat aui-clearfix">
            <h3 class="chat_title aui-text-center"><span>打印机晒鼓</span></h3>
            <?php if( is_array($model) && $model ):?>
            <div class="sp aui-row-10" style="overflow:hidden;">
                <?php foreach($model as $row):?>
                <a href="<?=Url::toRoute(['detail','id'=>$row['wx_id'],'item_id'=>$row['id']])?>" class="chat_cp aui-col-xs-6 aui-padded-0-10 aui-block">
                    <div class="cp">
                        <div class="img_in" style="width:100%; height:150px; overflow:hidden;">
                            <img src="<?=$row['cover']?>"  width="100%" height="150">
                        </div>
                        <div class="aui-padded-0-10 text_content">
                            <div class="cp_title aui-ellipsis-1"><?=$row['name']?></div>
                            <div class="price">
                                <div class="aui-pull-left" style="color:#ff0000;">￥<?=$row['price']?></div>
                                <div class="aui-pull-right">
                                    <span class="icon-uniE904" style="font-size:25px;">
                                        <span class="path1"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach;?>
            </div>
            <script type="text/formatTpl" id="show_cp">
                <%= for(var i=0; i<data.length; i++){ %>
                <a href="/shop/item/detail?id=1&item_id=<% data[i].id %>" class="chat_cp aui-col-xs-6 aui-padded-0-10 aui-block">
                    <div class="cp">
                        <div class="img_in" style="width:100%; height:150px; overflow:hidden;">
                            <img src="<% data[i].cover %>"  width="100%" height="150">
                        </div>
                        <div class="aui-padded-0-10 text_content">
                            <div class="cp_title aui-ellipsis-1"><% data[i].name %></div>
                            <div class="price">
                                <div class="aui-pull-left" style="color:#ff0000;">￥<% data[i].price %></div>
                                <div class="aui-pull-right">
                                    <span class="icon-uniE904" style="font-size:25px;">
                                        <span class="path1"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <%= } %>
            </script>
            <div class="aui-hidden aui-text-center loading" style="font-size:12px;"><img src="/images/loading-4.gif" width="20"></div>
            <?php if(count($model)>=$len):?>
                <!--<div id="item-more" class="item-more-90">
                    查看更多
                </div>-->
            <?php endif;?>
            <?php else:?>
                <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span>没有数据</div>
            <?php endif;?>
        </div>
    </div>
</div>
<footer class="footer_wechat">
    <div class="wrap">
        <div class="aui-content aui-text-center aui-border-t" style="font-size:15px;color:#999;">
            <a href="#" class="aui-col-xs-3 aui-padded-10 aui-border-r active-color"><span class="icon-uniE903"></span>首页</a>
            <a href="#" class="aui-col-xs-3 aui-padded-10 aui-border-r"><span class="icon-uniE901"></span>分类</a>
            <a href="#" class="aui-col-xs-3 aui-padded-10 aui-border-r"><span class="icon-uniE900"></span>订单</a>
            <a href="#" class="aui-col-xs-3 aui-padded-10"><span class="icon-uniE902"></span>购物车</a>
        </div>
    </div>
</footer>

<script> var q='',key='',len = <?=$len?>,startId = <?=$startId?>,action = '/shop/item/list?id=<?=$id?>'; </script>

<?php \app\assets\ZeptoAsset::register($this); ?>
<script>
    <?php $this->beginBlock('JS_END') ?>

    /*swiper*/
    (function(){
        new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            autoplay:5000,
            loop:true,
            autoplayDisableOnInteraction:false,
            speed:500,
        })
    })()

    /*搜索*/
    ;+function(){
        document.getElementsByClassName('icon-btn')[0].addEventListener('click', function(){
            if($.trim(document.getElementsByClassName('search_input')[0].value) === "") return;
            window.location.href = '' + val;
        }, false)
    }();

    /*上拉加载*/
    ;!function(){
        var bhold = true;
        var $sp = $('.sp');
        var noMessage = '<div class="aui-text-center">没有数据了</div>';
        var $loading = $('.loading');
        window.addEventListener('scroll', function(){
            if(document.body.scrollTop+250 >= parseInt(getComputedStyle(document.getElementsByClassName('chat')[0]).height)){
                if(bhold){
                    bhold = false;
                    $.ajax({
                        type:'get',
                        url: action,
                        data:{'key':key,'q':q,'startId':startId,'format':'json'},
                        dataType:'json',
                        beforeSend:function(){
                            $loading.removeClass('aui-hidden');
                        },
                        success:function(data){
                            $loading.addClass('aui-hidden');
                            if(data.status === 1){
                                startId = data.startId;
                                if( data.len < len ) return $sp.append(noMessage);
                                $sp.append(LANG('show_cp').formatTpl(data.data).end())
                            }else{
                                $sp.append(noMessage);
                            }
                            setTimeout(function(){
                                bhold = true;
                            },50)
                        }
                    });
                }
            }
        }, false)
    }();

    /*下拉刷新*/
    ;+function(){
        var pullRefresh = new auiPullToRefresh({
            "container": document.getElementsByClassName("aui-load-container")[0],
            "textRefresh": '正在刷新',
            "loadingCircleEl": document.getElementsByClassName('re-circle-loading')[0],
            "loadingDotEl": document.getElementsByClassName('re-circle')[0],
            "callback": function(status){
                if(status === "success"){
                    setTimeout(function(){
                        pullRefresh.cancelLoading();
                        window.location.reload()
                    },800)
                }
            }
        })
    }();

    <?php $this->endBlock(); ?>
</script>

<?php
    $this->registerJsFile('/js/lang.js');
    $this->registerJsFile('/js/aui/aui-pull-refresh.js');
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>