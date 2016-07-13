<?php
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\LoadingWidget;
use kartik\grid\GridView;

$this->title = '店铺装修';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("/js/jquery.min.js",['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile("/js/myjs.js");
?>

<style>
    /*.template-download{ display: none !important;}*/
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
            tmp = data.result.files[0];
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
<h4>已添加轮播图,排序越小越在前面</h4>


    <div class="market-customer-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-striped','id'=>'image-list'],
            'layout' => "{items}\n{pager}",
            'export' => false,
            'columns' => [
                [
                    'attribute'=>'imgurl',
                    'format'=>'html',
                    'content'=>function($model){
                        return '<img src="'.$model->imgurl.'" width="100">';
                    }
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'link',
                    'pageSummary'=>true,
                    'editableOptions'=> [
                        'formOptions' => ['action' => ['/home/editable']],
                        'showButtonLabels' => true,
                        'submitButton' => [
                            'label' => '保存',
                            'class' => 'btn btn-primary btn-sm',
                        ]
                    ]
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'title',
                    'pageSummary'=>true,
                    'editableOptions'=> [
                        'formOptions' => ['action' => ['/home/editable']],
                        'showButtonLabels' => true,
                        'submitButton' => [
                            'label' => '保存',
                            'class' => 'btn btn-primary btn-sm',
                        ]
                    ]
                ],
                [
                    'class'=>'kartik\grid\EditableColumn',
                    'attribute'=>'sort',
                    'pageSummary'=>true,
                    'editableOptions'=> [
                        'formOptions' => ['action' => ['/home/editable']],
                        'showButtonLabels' => true,
                        'submitButton' => [
                            'label' => '保存',
                            'class' => 'btn btn-primary btn-sm',
                        ]
                    ]
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'headerOptions' => ['style'=>'width:120px;'],
                    'header' => '操作',
                    'buttons' => [
                        'delete' => function($url,$model,$key){
                            return Html::button('删除',['class'=>'btn btn-danger btn-sm delete-btn','data-id'=>$key]);
                        }
                    ]

                ],
            ],
        ]); ?>
    </div>


<?= LoadingWidget::widget([  //动态添加的元素用不了
    'target' => '#image-list',
    'childTarget' => '.delete-btn',
    'confirmMessage'=>'确定删除该轮播图？',
    'th' => 'obj',      //$(this) 变量
    'url' => "delimg?id='+obj.attr('data-id')+'", //注意调用处外围有单引号
    'success' => 'function(msg){
            if(msg){
                obj.closest("tr").fadeOut(1000,function(){$(this).remove()}); //移除tr
            }
        }',
]);
?>