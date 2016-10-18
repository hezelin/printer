<?php
use yii\helpers\Url;
$this->title = '配件列表';
$dataHref = $fault_id? 'data-href':'href';          // 维修员页面跳转标记

use app\assets\HomeAsset;
HomeAsset::register($this);
$this->registerCssFile('/css/aui/css/aui-pull-refresh.css',['depends'=>['app\assets\AuicssAsset']]);
?>
<style>
    .aui-load-container{
        height:auto;
    }
    .re-circle-loading{
        vertical-align: bottom;
        padding-top:8px;
    }
    .aui-loading-title{
        padding-top:9px;
    }
    /*头部遮盖*/
    .header_line{
        height:42px;
    }

</style>
<div class="aui-content" id="top-search">
    <div class="aui-form">
        <div class="aui-input-row">
            <label id="category-list" class="aui-input-addon aui-text-info">
                <span class="cate-name">全部</span>
                <span class="aui-iconfont aui-icon-sort aui-size-20"></span>
            </label>
            <input id="search-input" type="text" class="aui-input"/>
            <span id="search-btn" class="aui-input-addon aui-iconfont aui-icon-search aui-text-info"></span>
        </div>
    </div>

    <?= \yii\helpers\Html::tag('ul',\app\modules\shop\models\Shop::getMenu($id),[
            'class'=>'aui-list-view',
            'id'=>'fixed-menu',
        ]);
    ?>
</div>
<div class="header_line"></div>
<div class="aui-load-container">
    <div class="aui-load-wrap aui-col-xs-12 aui-text-center">
        <div class="re-circle-loading"><img src="/images/loading-4.gif" width="30"></div>
        <div class="aui-loading-title"></div>
    </div>
    <div class="aui-content" id="task-list">
        <?php if( is_array($model) && $model ):?>
            <ul class="aui-list-view" id="item-list">
                <?php foreach($model as $row):?>
                    <li class="aui-list-view-cell aui-img">
                        <a class="aui-border-b" <?=$dataHref?>="<?=Url::toRoute(['detail','id'=>$row['wx_id'],'item_id'=>$row['id']])?>">
                        <img class="aui-img-object aui-pull-left" src="<?=$row['cover']?>">
                        <div class="aui-img-body">
                            <span class="aui-ellipsis-2" style="height:50px; margin-right: 12px;">
                                <?=$row['name']?>
                            </span>
                            <p class="aui-ellipsis-1 aui-text-right" style="margin-top:10px;">
                                <span class="aui-red-color aui-pull-left">￥<?=$row['price']?></span>
                                <?=$row['category']?>
                            </p>
                        </div>
                        </a>
                    </li>
                <?php endforeach;?>
            </ul>

            <?php if(count($model)>=$len):?>
                <div id="item-more" class="item-more-90 aui-border-tb" style="margin:0; width:100%; border-radius:0; border:0; height:40px; line-height: 40px;">
                    查看更多
                </div>
            <?php endif;?>

        <?php else:?>
            <div class="blank-text"> <span class="aui-iconfont aui-icon-warn"></span>没有数据</div>
        <?php endif;?>
    </div>
</div>
<?php
\app\assets\ZeptoAsset::register($this);
?>
<script>

    var q='',
        key='',
        len = <?=$len?>,
        startId = <?=$startId?>,
        dataHref = '<?=$fault_id? 'data-href':'href'?>',
        action = '/shop/parts/list?id=<?=$id?>';
    function getHtml(d){
        var html = [];

        html.push('<li class="aui-list-view-cell aui-img">');
        html.push('<a class="aui-border-b" '+dataHref+'="detail?id='+d.wx_id+'&item_id='+d.id+'">');
        html.push('<img class="aui-img-object aui-pull-left" src="'+ d.cover +'">');
        html.push('<div class="aui-img-body"><span class="aui-ellipsis-2" style="height:50px;">');
        html.push(d.name);
        html.push('</span> <p class="aui-ellipsis-1 aui-text-right" style="margin-top:10px;">');
        html.push('<span class="aui-red-color aui-pull-left">￥'+ d.price +'</span>');
        html.push(d.category);
        html.push('</p></div></a></li>');
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
                        $('#item-list').append( html.join('') );
                    else
                        $('#item-list').html( html.join('') );

                    if( resp.len < len )
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                    else
                        $('#item-more').removeClass('item-more-end').text('查看更多');
                }else{
                    $('#item-more').addClass('item-more-end').text('没有数据了');
                    if(type === 'html')
                        $('#item-list').html('');
                }
            }
        });
    }
    <?php $this->beginBlock('JS_END') ?>
    $(function(){

        // 点击加载更多
        $('#item-more').click(function(){
            if( $(this).hasClass('item-more-end') ) return false;
            getData(action,key,startId,q,'append');
        });

        //  绑定配件跳转
        $("#item-list").on("click","a",function(){
            if( $(this).attr("data-href") )
                location = $(this).attr("data-href")+"&fault_id=<?=$fault_id?>";
        });

        // 搜索筛选
        $('#search-btn').click(function(){
            if( $('#search-input').val() ){
                q = $('#search-input').val();
                getData(action,key,'',q,'html');
            }
        });

        // 点击类目
        $('#fixed-menu').on('click','li',function(){
            key = $(this).attr('data-key');
            var name = $(this).text();
            getData(action,key,'','','html');

            $('#category-list .cate-name').text(name);
            $('#fixed-menu').hide();
            $('#category-list').removeClass('.has-show');
        });

        // 弹出类目
        $('#category-list').click(function(){
           if($(this).hasClass('.has-show')){
               $('#fixed-menu').hide();
               $(this).removeClass('.has-show');
           }else{
               $('#fixed-menu').show();
               $(this).addClass('.has-show');
           }
        });

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
    });

    /*下拉刷新*/

    <?php $this->endBlock(); ?>
</script>
<?php
$this->registerJsFile('/js/aui/aui-pull-refresh.js');
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>