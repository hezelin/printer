<?php
use yii\helpers\Url;

$this->title = '我的业绩';

?>
<p style="width: 100%; height: 15%; float:left;">&nbsp;</p>
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
        width: 80%; background-color: #efefef;font-size: 14px;
        /*box-shadow: 1px 1px 2px #cccccc; */
        border: 1px solid #EEEEEE;
        text-align: center; height: 36px; line-height: 36px;
        margin: 15px auto; border-radius: 4px; color: #666666;
    }
    .item-more-end{
        background-color: #FFFFFF;color: #cccccc;
        border: 0px;
    }
    #top{ height: 180px; background-color: #2edcff; color: #FFFFFF; text-align: left;
        position: relative;
    }
    .score-title{ height: 30px; line-height: 20px; font-size: 14px; padding-left: 15px;
        float: left; width: 100%;}

    dl,dd{ list-style: none; padding: 0; margin: 0;}
    .top-content{ height: 140px;  text-align: center; position: relative;
        display: block;
    }
    .top-content dd{ width: 50%; display: block; float: left; height: 60px;}
    .top-content span{ display: block; height: 20px; line-height: 20px;}
    .top-content .num{ display: block; height: 30px; line-height: 30px; font-size: 30px; font-weight: 600;}

    #item-list-wrap .list-title{ height: 60px; line-height: 60px;}
    #item-list-wrap li{ height: 40px; line-height: 40px; border-bottom: 1px solid #cccccc}
    #item-list-wrap li span{ width: 19%; text-align: left; display: inline-block; padding-left: 1%;}
</style>

<div id="top">
    <p class="score-title"><?=date('m',time())?>月份统计</p>
    <dl class="top-content">
        <dd>
            <span>维修次数</span>
            <span class="num"><?=$lastMonth['total_fault']?></span>
        </dd>
        <dd>
            <span>维修时长(h)</span>
            <span class="num"><?=$lastMonth['fault_time']?></span>
        </dd>
        <dd>
            <span>反应速度(km/h)</span>
            <span class="num"><?=$lastMonth['total_km']?></span>
        </dd>
        <dd>
            <span>评价平均分</span>
            <span class="num"><?=$lastMonth['total_score']?></span>
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
    <div id="item-more" class="item-more item-more-end">
<!--        没有数据了-->
    </div>
<?php else:?>
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