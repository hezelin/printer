<?php
use yii\helpers\Url;

$this->title = '我的业绩';

?>
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

<?php if(count($model)>=10):?>
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
            url: '/maintain/default/score?id=<?=$id?>',
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