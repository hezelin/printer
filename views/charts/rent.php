<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
    $this->title='租借统计数据';
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
    echo Html::beginForm('/charts/rent','get',['class'=>'form-inline']);
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
    <script>
        <?php $this->beginBlock('JS_END') ?>
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '租借统计'
            },
            xAxis: {
                categories: <?=json_encode($charts['cate'])?>,
                tickmarkPlacement: 'on',
                title: {
                    'text':'日期'
                }
            },
            yAxis: [{
                labels: {
                    format: '{value}次',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: '累计租借',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }, {
                title: {
                    text: '次数',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} 次',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }
            }],
            tooltip: {
                shared: true,
                valueSuffix: '次'
            },
            series: <?=json_encode($charts['series'])?>
        });
        <?php $this->endBlock();?>
    </script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>