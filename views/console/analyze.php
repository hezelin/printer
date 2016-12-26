<?php
use yii\helpers\Url;

$this->title = '数据统计';
?>
<style>
    body{ background-color: #f5f5f5 !important; padding-top:20px;}
    .bg-1{
        background-color: #94afff;
    }
    .bg-2{
        background-color: #ffca74;
    }
    .bg-3{
        background-color: #CC99CC;
    }
    .bg-4{
        background-color: #66CC99;
    }
    .bg-5{
        background-color: #CCCCCC;
    }
    .bg-6{
        background-color: #FF6666;
    }
    .ana-box{
        margin: 0 0 15px 0;
        padding: 5px 15px;
        border-radius: 4px;
        position: relative;
        color: #FFFFFF;
        display: block;
    }
    .chart-box{
        border-radius: 6px !important;
        margin-bottom: 30px;
        box-shadow: 0 1px 4px #ccc;
        background-color: #fff;
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
        top: 12px;
        right: 55px;
        z-index: 999;
    }
    .ana-fixed-btn:hover{color: orange;}
</style>
<!--<pre>
    <?php /*echo json_encode($machine,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)*/?>
</pre>-->
<div class="row">
    <div class="col-md-6">
        <div id="maintain" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/maintainer'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>

    <div class="col-md-6">
        <div class="chart-box">
            <div id="machine"></div>
        </div>
    </div>

    <div class="col-md-6">
        <div id="rent" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/rent'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>
    <div class="col-md-6">
        <div id="container" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/fault'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>
    <div class="col-md-6">
        <div id="order" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/order'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>

    <div class="col-md-6">
        <div id="item" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/item'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>

    <div class="col-md-6">
        <div id="order2" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/order'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>

    <div class="col-md-6">
        <div id="rental" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/rental'])?>"  class="ana-fixed-btn" title="查看详情"><i class="glyphicon glyphicon-resize-full"></i></a>
    </div>


</div>
<script>
    <?php $this->beginBlock('JS_END') ?>

    $('#item').highcharts({
        chart: {
            zoomType: 'xy',
            height: 400,
            x: -20
        },
        title: {
            text: '耗材库存走势'
        },
        xAxis: [{
            categories: <?=json_encode($item['cate'])?>,
            crosshair: true
        }],
        yAxis: [{
            labels: {
                format: '{value}个',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: null,
            opposite: true
        }, {
            title: null,
            labels: {
                format: '{value} 元',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        series: <?=json_encode($item['series'])?>
    });
    $('#container').highcharts({
        chart: {
            height: 340
        },
        title: {
            text: '维修统计'
        },
        xAxis: {
            categories: <?=json_encode($charts['cate'])?>,
            tickmarkPlacement: 'on'
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
        series: <?=json_encode($charts['series'])?>
    });

    $('#machine').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            height: 300,
            type: 'pie'
        },
        exporting: {
            enabled: false
        },
        title: {
            text: '机器来源(<?=$machine['total']?>台)'
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: {point.y}<br/>',
            shared: true,
            valueSuffix: ' 台'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: <?=json_encode($machine['series'])?>
    });

    /*$('#machine2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            height: 300,
            type: 'pie'
        },
        exporting: {
            enabled: false
        },
        title: {
            text: '机器状态'
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: {point.y}<br/>',
            shared: true,
            valueSuffix: ' 台'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: =json_encode($machine['series2'])?>
    });*/

    $('#rent').highcharts({
        chart: {
            height: 340
        },
        title: {
            text: '租借统计',
            x: -10
        },
        xAxis: {
            categories: <?=json_encode($rent['cate'])?>,
            tickmarkPlacement: 'on'
        },
        yAxis: {
            title: {
                text: '次数'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: '次'
        },
        series: <?=json_encode($rent['series'],JSON_UNESCAPED_UNICODE)?>
    });

    $('#order').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: '当月累计订单'
        },
        xAxis: [{
            categories: <?=json_encode($order['cate'])?>,
            crosshair: true
        }],
        yAxis: [{
            labels: {
                format: '{value} 个',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            title: {
                text: null,
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            }
        },{
            gridLineWidth: 0,
            title: {
                text: null,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} 元',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        series: <?=json_encode($order['series'])?>
    });

    $('#order2').highcharts({
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: '每日新增订单情况'
        },
        xAxis: [{
            categories: <?=json_encode($order['cate'])?>,
            crosshair: true
        }],
        yAxis: [ {
            labels: {
                format: '{value} 个',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            title: {
                text: '数量',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true

        },{
            gridLineWidth: 0,
            title: {
                text: '总金额',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                format: '{value} 元',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        series: <?=json_encode($order['day'])?>
    });

    $('#maintain').highcharts({
        chart:{
            height: 300
        },
        title: {
            text: '<?=$maintainer['tips']['title']?>'
        },
        xAxis: {
            categories: <?=json_encode($maintainer['cate'])?>,
            tickmarkPlacement: 'on'
            /*title: {
                'text':'月份'
            }*/
        },
        yAxis: {
            title: {
                text: '数量'
            }
        },
        tooltip: {
            shared: true
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
        series: <?=json_encode($maintainer['series'])?>
    });

    $('#rental').highcharts({
        title: {
            text: '机器租金统计'

        },
        xAxis: {
            categories: <?=json_encode($rental['cate'])?>,
            tickmarkPlacement: 'on',
            title: {
                'text':'月份'
            }
        },
        yAxis: {
            title: {
                text: '金额'
            }
        },
        tooltip: {
            shared: true
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
        series: <?=json_encode($rental['series'])?>
    });

    <?php $this->endBlock();?>
</script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
