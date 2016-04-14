<?php
use yii\helpers\Url;

$this->title = '我的业绩';

?>
<style>
    .item-list{
        display: block;
        padding: 12px 0;
        width: 90%;
        margin: 0 5%;
        border-bottom: 1px #e3e3e3 solid;
        font-size: 16px;
    }
    .item-list img{
        width: 80px;
        height: 60px;
        float: left;
        margin-right: 10px;
    }
    .item-list span h5{
        height: 55px;
        color: #666;
        font-weight: normal;
        font-size: 16px;
    }
    .mtm_p{
        text-align: right;
        color: #999;
        font-size: 14px;
    }
    .mtm_p b{
        float: left;
        color: #b10000;
        font-size: 16px;
    }

    .item-more{
        width: 100%; background-color: #fff;font-size: 14px;
        /*box-shadow: 1px 1px 2px #cccccc; */
        border: 1px solid #EEEEEE;
        text-align: center; height: 36px; line-height: 36px;
        margin: 15px auto; border-radius: 4px; color: #666666;
    }
    .item-more-end{
        display: none !important;
    }
    #top{ height: 180px;  color: #FFFFFF; text-align: left;
        position: relative;
        background-color: #36a1db;
        background:-webkit-gradient(linear,0 0,100% 100%, from(#36a1db), to(#36c1db));
        background: -moz-linear-gradient(left top, #36a1db,#36c1db);
        background: -webkit-linear-gradient(left top, #36a1db,#36c1db);
        background: -o-linear-gradient(left top, #36a1db,#36c1db);
    }
    .score-title{ height: 30px; line-height: 20px; font-size: 14px; padding-left: 15px;
        float: left; width: 100%;}

    dl,dd{ list-style: none; padding: 0; margin: 0;}
    .top-content{ height: 140px;  text-align: center; position: relative;
        display: block;
    }
    .top-content dd{ width: 50%; display: block; float: left; height: 90px; padding: 15px 0;
    border-bottom: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5;}
    .top-content dd:nth-child(even){border-right: none;}
    .top-content .num{ display: block; height: 40px; line-height: 40px; font-size: 40px; font-weight: 600;}
    .top-content .end{ font-size: 14px; padding-left: 5px;}
    .top-content .tips{ line-height: 16px; font-size: 14px;}

    #item-list-wrap{ margin-top: 15px; background-color: #fff; font-size: 12px;}
    #item-list-wrap .list-title{ height: 50px; line-height: 50px;border-bottom: 1px solid #cccccc; background-color: transparent !important;}
    #item-list-wrap li{ height: 40px; line-height: 40px; }
    #item-list-wrap li:nth-child(odd){ background-color: #EDF5F2;}
    #item-list-wrap li span{ width: 19%; text-align: center; display: inline-block;}
</style>

<div class="aui-padded-10"><p class="aui-border-left aui-p-l">今月份的统计</p></div>
<div id="top">
    <dl class="top-content">
        <dd>
            <span class="num"><?=$lastMonth['total_fault']?><span class="end">次</span></span>
            <span class="tips">维修次数</span>
        </dd>
        <dd>
            <span class="num"><?=$lastMonth['fault_time']?><span class="end">h</span></span>
            <span class="tips">维修时长</span>
        </dd>
        <dd>
            <span class="num"><?=$lastMonth['total_km']?><span class="end">km/h</span></span>
            <span class="tips">反应速度</span>
        </dd>
        <dd>
            <span class="num"><?=$lastMonth['total_score']?><span class="end">分</span></span>
            <span class="tips">评价平均</span>
        </dd>
    </dl>
</div>

<ul id="item-list-wrap">
    <?php if($model):?>
        <li class="list-title"><span>月份</span><span>维修次数</span><span>维修时长</span><span>反应速度</span><span>平均分</span></li>
    <?php foreach($model as $r):?>
        <li>
            <span><?=$r['date']?></span><span><?=$r['total_fault']?></span>
            <span><?=$r['fault_time']?></span>
            <span><?=$r['total_km']?></span>
            <span><?=$r['total_score']?></span>
        </li>
    <?php endforeach;?>
    <?php endif;?>
</ul>

<?php if(count($model)<10):?>
    <div id="item-more" class="item-more">
        查看更多
    </div>
<?php endif;?>

<script>
    var startId = <?=$startId?>;
    function getHtml(d){
        var html = [];
        html.push('<li><span>'+ d.date +'</span>');
        html.push('<span>'+ d.total_fault +'</span>');
        html.push('<span>'+ d.fault_time +'</span>');
        html.push('<span>'+ d.total_km +'</span>');
        html.push('<span>'+ d.total_score +'</span>');

        return html.join('');
    }
    function getData(startId)
    {
        $.ajax({
            type:'get',
            url: '/m/index/<?=$id?>',
            data:{'startId':startId,'format':'json'},
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
                }else{
                    $('#item-more').addClass('item-more-end').text('没有数据了');
                }
            }
        });
    }
    <?php $this->beginBlock('JS_END') ?>
    $(function(){
        // 点击加载更多
        $('#item-more').click(function () {
            if ($(this).hasClass('item-more-end')) return false;
            getData(startId);
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>