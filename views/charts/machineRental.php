<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
$this->title = '收租记录';

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

    <div class="alert alert-info" role="alert">
        <p>机器资料：<?=$rent['brand'],' , ',$rent['type'],' , ',$rent['series_id']?></p>
        <p>用户资料：<?=$rent['name'],' , ',$rent['address']?></p>
        <p>租借方案：最低消费<?=$rent['monthly_rent']?>元，付款周期：<?=$rent['rent_period']?>个月，黑白 <?=$rent['black_white']?>元/每张
            <?php
            if($rent['colours'])
                echo '，彩色：',$rent['colours'],'元/每张';
            ?></p>
    </div>

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
                type: 'column'
            },
            title: {
                text: '租金统计'
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
                    text: '租金'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: '元'
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
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

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
        [
            'attribute'=>'add_time',
            'label'=>'记录时间',
            'value'=>function($model){
                return date('Y-m-d H:i',$model->add_time);
            }
        ],
        'black_white',
        'colour',
        'total_money',
        'exceed_money',
        [
            'attribute'=>'sign_img',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($model){
                return Html::a(Html::img($model->sign_img,['style'=>'width:40px;']),$model->sign_img,['class' => 'fancybox','rel'=>'group1']);
            }
        ],
        'name',
        [
            'attribute'=>'status',
            'filter'=>[1=>'待确认',2=>'已确认'],
            'options'=>['style'=>'width:100px'],
            'value'=>function($model){
                return $model->status==1? '待确认':'已确认';
            }
        ]
    ],
]); ?>

<?php
// fancybox 图片预览插件
echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '100%',
        'height' => '100%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
?>