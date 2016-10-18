<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
$this->title = '商品列表';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="">
    <a href="<?=Url::toRoute(['/shop/backend/add'])?>" class="btn btn-info">添加耗材资料</a>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        'id',
        [
            'attribute'=>'cover_images',
            'headerOptions'=>['style'=>'width:160px'],
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                $covers = json_decode($data->cover_images,true);
                $html = [];
                foreach($covers as $cover){
                    $html[] = Html::a(Html::img($cover,['width'=>40]),str_replace('/s/','/m/',$cover),['class' => 'fancybox','rel'=>'group1']);
                }
                return join("\n",$html);
            }
        ],
        'name',
        'market_price',
        'price',
        'amount',
        'use_score',
        'give_score',
        [
            'attribute'=>'add_attr',
            'headerOptions'=>['style'=>'width:160px'],
            'format' => 'html',
            'value'=>function($data)
            {
                $attr = json_decode($data->add_attr,true);
                $html = [];
                foreach($attr as $row){
                    $html[] = Html::tag('div',$row['name'].' : '.$row['value']);
                }
                return join("\n",$html);
            }
        ],
        [
            'attribute'=>'category_id',
            'headerOptions'=>['style'=>'width:80px'],
            'filter' => \app\modules\shop\models\Shop::getCategory(),
            'value' => function($data){
                return \app\modules\shop\models\Shop::getCategory($data->category_id);
            }
        ],
        [
            'attribute'=>'status',
            'headerOptions'=>['style'=>'width:80px'],
            'filter' => [1=>'上架中',2=>'下架中'],
            'value' => function($data)
            {
                return $data->status==1? '上架中':'下架中';
            }
        ],
        [
            'attribute'=>'add_time',
            'format'=>['date','Y-H-d H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:140px'],
            'template' => '{view} &nbsp; {status} &nbsp; {update} &nbsp; {qrcode} &nbsp; {delete}',
            'buttons' => [
                'status' => function($url,$model,$key){
                    $icon = $model->status == 1? 'glyphicon glyphicon-arrow-down':'glyphicon glyphicon-arrow-up';
                    return Html::a('<i class="'.$icon.'"></i>',Url::toRoute(['status','id'=>$key,'status'=>$model->status]),[
                        'title' => $model->status == 1? '下架':'上架'
                    ]);
                },
                'qrcode' => function($url,$model,$key){
                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',Url::toRoute(['code/item','id'=>$key,'wx_id'=>$model->wx_id]),['title'=>'配件二维码']);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-trash"></i>',$url,[
                        'title'=>'删除商品',
                        'data-method'=>'post',
                        'data-pjax'=>0,
                        'data-confirm'=>'确定删除这个商品？'
                    ]);
                },
                /*'link' => function($url,$model,$key){
                    return Html::tag('span','<i class="glyphicon glyphicon-link"></i>',[
                        'class'=>'tool-tips zClip',
                        'data-clipboard-text'=>Url::toRoute(['/shop/item/detail','id'=>$model['wx_id'],'item_id'=>$model['id']],'http')
                    ]);
                }*/
            ]
        ]

    ],
]);



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

    <div id="d_debug"></div>

<script>
    <?php $this->beginBlock('JS_END') ?>

    /*var clip = new ZeroClipboard($(".zClip"),{forceHandCursor:true});

    clip.on("ready", function() {
        $('#global-zeroclipboard-html-bridge').attr({
            'data-toggle': 'tooltip',
            'data-title': '复制到剪贴板',
            'data-placement': 'left'
        });
        $('#global-zeroclipboard-html-bridge').tooltip({
            container: 'body',
            trigger: 'hover'
        });

        this.on("aftercopy", function(event) {
            $('#global-zeroclipboard-html-bridge').attr("title","复制成功！").tooltip("fixTitle").tooltip("show").attr("title","复制到剪贴板").tooltip("fixTitle");
        });

    });

    clip.on("error", function(event) {
        alert("浏览器不支持复制！");
        ZeroClipboard.destroy();
    });*/

    <?php $this->endBlock();?>
</script>

<?php
//    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
//    $this->registerJsFile('/js/mobile/ZeroClipboard.min.js',['depends'=>['yii\web\JqueryAsset']]);
?>
