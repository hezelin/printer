<?php
    use dosamigos\fileupload\FileUploadUI;
    use yii\helpers\Url;
    use app\components\LoadingWidget;

    $this->title = '店铺装修';
    $this->registerJsFile("/js/jquery.min.js",['position' => \yii\web\View::POS_HEAD]);
    $this->registerJsFile("/js/myjs.js");
?>

<style>
    .textedit {
        padding-right: 18px;
        background: url('/images/pencil.png') no-repeat right;
        background-size: 16px;
    }
</style>

<h3>首页轮播图片设置</h3>
<hr>

<div class="alert alert-info">
    <strong>温馨提示</strong>：为保证显示质量，请上传长宽比例为2:1的图片。
</div>
<!--上传图片插件-->
<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
//    'url' => ['home/receiveimage', 'weixinid' => $_GET['id']],
    'url' => Url::toRoute(['home/receive-image', 'weixinid' => $wx_id]),
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*',
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000,
        //'maxNumberOfFiles' => 5,  //这里是同时存在页面的数量
    ],
    // 打印日志
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                    $("#newadd").show();
                                    $("#newtable").show();
                                }',
        'fileuploadfail' => 'function(e, data) {
                                }',
        'fileuploaddestroy' => 'function(e, data) {
                                    //还得添加删除按钮事件$(this).find(tr).remove();
                                }',
    ],
]);
?>

<hr>
<h4>已添加轮播图</h4>


    <div class="market-customer-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-striped'],
//        'layout' => "{items}\n{pager}",
            'export' => false,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'imageurl:image',
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'last_trace',
                    'pageSummary'=>true,
                    'editableOptions'=> [
                        'formOptions' => ['action' => ['/market/editable/data']],
                        'showButtonLabels' => true,
                        'submitButton' => [
                            'label' => '保存',
                            'class' => 'btn btn-primary btn-sm',
                        ]
                    ]
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'remark',
                    'pageSummary'=>true,
                    'editableOptions'=> [
                        'formOptions' => ['action' => ['/market/editable/data']],
                        'showButtonLabels' => true,
                        'submitButton' => [
                            'label' => '保存',
                            'class' => 'btn btn-primary btn-sm',
                        ]
                    ]
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} &nbsp; {log} &nbsp; {free}',
                    'headerOptions' => ['style'=>'width:120px;'],
                    'header' => '操作',
                    'buttons' => [
                        'log' => function($url,$model,$key){
                            return Html::a('<i class="glyphicon glyphicon-info-sign"></i>',$url,['title'=>'跟踪记录']);
                        },
                        'free' => function($url,$model,$key){
                            return Html::a('<i class="glyphicon glyphicon-refresh"></i>',['free','id'=>$key,'from'=>Yii::$app->request->url],['title'=>'释放资料',
                                'data' => [
                                    'confirm' => '确定要释放此选项吗?',
                                    'method' => 'post'
                                ]]);

                        }
                    ]

                ],
            ],
        ]); ?>
    </div>

<?php
//echo Html::a('删除', ['delimg', 'id' => $model->id], [
//    'class' => 'btn btn-danger',
//    'data' => [
//        'confirm' => '确定删除该轮播图？',
//        'method' => 'post',
//    ],
//]); ?>

<?= LoadingWidget::widget([  //动态添加的元素用不了
    'target' => '.table',
    'childTarget' => '.deletebtn',
    'confirmMessage'=>'确定删除该轮播图？',
    'th' => 'obj',      //$(this)
    'url' => "delimg/'+$(this).attr('data-id')+'", //注意调用处外围有单引号
    'success' => 'function(msg){
            if(msg){
                obj.parent().parent().fadeOut(1000,function(){$(this).remove()}); //移除hr
            }
        }',
]);
?>