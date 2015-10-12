<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
    $this->title='机器租金统计';
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
    echo Html::beginForm('/charts/rental','get',['class'=>'form-inline','id'=>'line-form']);
    echo Html::beginTag('div',['class'=>'input-group','style'=>'margin-left:30px']);
    echo DatePicker::widget([
        'name'  => 'start',
        'value'  => $charts['start'],
        'dateFormat' => 'yyyyMMdd',
        'options'=>['class'=>'form-control date-input']
    ]);
    echo Html::tag('div','到',['class'=>'input-group-addon']);
    echo DatePicker::widget([
        'name'  => 'end',
        'value'  => $charts['end'],
        'dateFormat' => 'yyyyMMdd',
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
            title: {
                text: '机器租金统计'

            },
            xAxis: {
                categories: <?=json_encode($charts['cate'])?>,
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
            series: <?=json_encode($charts['series'])?>
        });

        <?php $this->endBlock();?>
    </script>
<?php
\app\assets\HightchartsAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>

