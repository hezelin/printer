<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
    $this->title='订单统计数据';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .input-group-addon{
        border-left: none;
        border-right: none;
    }
    .date-input{
        width: 150px !important;
    }
</style>
<div class="row">

<?php
    echo Html::beginForm('/charts/order','get',['class'=>'form-inline']);
    echo Html::beginTag('div',['class'=>'input-group','style'=>'margin-left:30px']);
    echo DatePicker::widget([
        'name'  => 'start',
        'value'  => $charts['start'],
        'dateFormat' => 'yyyy-MM-dd',
        'options'=>['class'=>'form-control date-input']
    ]);
    echo Html::tag('div','到',['class'=>'input-group-addon']);
    echo DatePicker::widget([
        'name'  => 'end',
        'value'  => $charts['end'],
        'dateFormat' => 'yyyy-MM-dd',
        'options'=>['class'=>'form-control date-input']
    ]);
    echo Html::endTag('div');
    echo Html::submitButton('筛选',['class'=>'btn btn-info','style'=>'margin-left:15px']);
    echo Html::endForm();
?>

</div>
<div id="container"></div>
<div id="new-day"></div>
    <script>
        <?php $this->beginBlock('JS_END') ?>
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '订单总数据统计'
            },
            xAxis: [{
                categories: <?=json_encode($charts['cate'])?>,
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
            series: <?=json_encode($charts['series'])?>
        });

        // 新增图表
        $('#new-day').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '新增加订单数据'
            },
            xAxis: [{
                categories: <?=json_encode($charts['cate'])?>,
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
            series: <?=json_encode($charts['day'])?>
        });
        <?php $this->endBlock();?>
    </script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>