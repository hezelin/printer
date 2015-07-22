<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
    $this->title='商城库存数据';
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
    echo Html::beginForm('/charts/item','get',['class'=>'form-inline']);
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
<p>&nbsp;</p>
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
    <script>
        <?php $this->beginBlock('JS_END') ?>
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: '耗材库存走势'
            },
            xAxis: [{
                categories: <?=json_encode($charts['cate'])?>,
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
            /*legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },*/
            series: <?=json_encode($charts['series'])?>
        });
        <?php $this->endBlock();?>
    </script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>