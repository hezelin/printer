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
        border-radius: 4px !important;
        margin-bottom: 30px;
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
        top: 5px;
        left: 30px;
        z-index: 999;
    }
</style>
<div class="row">
    <div class="col-md-6">
        <div id="item" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/item'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>
    <div class="col-md-6">
        <div id="machine" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/machine'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>
    <div class="col-md-6">
        <div id="stock">
            <?php if($stock):?>
                <table class="table">
                    <tr><th>商品名称</th><th>库存数量</th></tr>
                    <?php foreach($stock as $s):?>
                        <tr><td><?=$s['name']?></td><td><?=$s['amount']?></td></tr>
                    <?php endforeach;?>
                </table>
            <?php endif;?>
        </div>
    </div>
    <div class="col-md-6">
        <div id="rent" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/rent'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>
    <div class="col-md-6">
        <div id="container" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/fault'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>
    <div class="col-md-6">
        <div id="order" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/order'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>
    <div class="col-md-6">
        <div id="maintain" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/maintainer'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>

    <div class="col-md-6">
        <div id="order2" class="chart-box"></div>
        <a href="<?=Url::toRoute(['charts/order'])?>"  class="btn btn-default btn-sm ana-fixed-btn">查看</a>
    </div>

</div>
<script>
    <?php $this->beginBlock('JS_END') ?>
    $('#item').highcharts({
        chart: {
            zoomType: 'xy'
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
            title: {
                text: '总量',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }, {
            title: {
                text: '成本',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
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
            type: 'column'
        },
        title: {
            text: '维修统计'
        },
        xAxis: {
            categories: <?=json_encode($charts['cate'])?>,
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
        series: <?=json_encode($charts['series'])?>
    });

    $('#machine').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '机器库存数据'
        },
        xAxis: {
            categories: <?=json_encode($machine['cate'])?>,
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
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
            shared: true,
            valueSuffix: ' 台'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: <?=json_encode($machine['series'])?>
    });

    $('#rent').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '租借统计'
        },
        xAxis: {
            categories: <?=json_encode($rent['cate'])?>,
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
        series: <?=json_encode($rent['series'])?>
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
        yAxis: [ {
            gridLineWidth: 0,
            title: {
                text: '总积分',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            labels: {
                format: '{value} 分',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true
        },{
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
            gridLineWidth: 0,
            title: {
                text: '总积分',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            labels: {
                format: '{value} 分',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true
        },{
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
        title: {
            text: '<?=$maintainer['tips']['title']?>'
        },
        xAxis: {
            categories: <?=json_encode($maintainer['cate'])?>,
            tickmarkPlacement: 'on',
            title: {
                'text':'月份'
            }
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
    <?php $this->endBlock();?>
</script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
