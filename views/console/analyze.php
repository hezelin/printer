<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;

    $this->title = '数据统计'
?>
<style>
    .bg-1{
        background-color: #94afff;
    }
    .bg-2{
        background-color: #ffca74;
    }
    .ana-box{
        margin: 0 0 15px 15px;
        padding: 5px 15px;
        border-radius: 4px;
        position: relative;
        color: #FFFFFF;
        display: block;
    }
    .ana-box:hover{
        color: #FFFFFF;
        text-underline: none;
    }
    .ana-hei-40{
        height: 40px;
        line-height: 40px;
        margin: 0;
        padding: 0;
        font-size: 18px;
    }
    .ana-sm-3{
        width: 100px;
        text-align: left;
        float: left;
    }
    .icon-position{ position: absolute; right: 15px; top: 65px;}

    .input-group-addon{
        border-left: none;
        border-right: none;
    }
    .date-input{
        width: 150px !important;
    }
    .ana-fixed-btn{
        position: absolute;
        top: 0px;
        left: 30px;
        z-index: 999;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <a href="<?=Url::toRoute(['charts/item'])?>" class="ana-box bg-1" title="查看库存变化">
            <h3>商城库存</h3>
            <p class="ana-hei-40"><span class="ana-sm-3">商品种类</span><span class="ana-sm-3">商品总量</span><span class="ana-sm-3">总成本</span><span class="ana-sm-3">预售价</span></p>
            <p class="ana-hei-40"><span class="ana-sm-3"><?=$item['cate_count']?></span>
                <span class="ana-sm-3"><?=$item['item_count']?></span><span class="ana-sm-3">¥ <?=$item['cost_price']?></span>
                <span class="ana-sm-3">¥ <?=$item['sell_price']?></span></p>
            <i class="icon-position glyphicon glyphicon-menu-right"></i>
        </a>
    </div>
    <div class="col-md-6">
        <a href="<?=Url::toRoute(['charts/machine'])?>" class="ana-box bg-2" title="查看库存变化">
            <h3>机器库存</h3>
            <p class="ana-hei-40"><span class="ana-sm-3">机器总量</span><span class="ana-sm-3">已租借数</span><span class="ana-sm-3">闲置数量</span><span class="ana-sm-3">报废数量</span></p>
            <p class="ana-hei-40"><span class="ana-sm-3"><?=$machine['free_count']+$machine['rent_count']+$machine['scrap_count']?></span>
                <span class="ana-sm-3"><?=$machine['rent_count']?></span><span class="ana-sm-3"><?=$machine['free_count']?></span>
                <span class="ana-sm-3"><?=$machine['scrap_count']?></span></p>
            <i class="icon-position glyphicon glyphicon-menu-right"></i>
        </a>
    </div>
    <div class="col-md-6">
        <a href="<?=Url::toRoute(['charts/fault'])?>" class="btn btn-sm btn-default ana-fixed-btn">查看</a>
        <div id="fault-charts"></div>
    </div>
    <div class="col-md-6">
    </div>

</div>
<div id="container"></div>
<script>
    <?php $this->beginBlock('JS_END') ?>
    $('#fault-charts').highcharts({
        title: {
            text: '维修统计'
        },
        xAxis: {
            categories: <?=json_encode($fault['cate'])?>,
            tickmarkPlacement: 'on',
            title: {
                'text':'日期'
            }
        },
        yAxis: {
            title: {
                text: '数量'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: '次'
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: <?=json_encode($fault['series'])?>
    });
    <?php $this->endBlock();?>
</script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
