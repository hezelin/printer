<?php
$this->title = '店铺装修';
use dosamigos\fileupload\FileUploadUI;

?>
<h3>首页轮播图片设置</h3>
<?php //$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>


<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
    'url' => ['home/receiveimage', 'id' => $model->image],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*'
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // 打印日志
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
    ],
]);
?>
