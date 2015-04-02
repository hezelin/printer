<?php
    $this->title = '店铺装修';
    use dosamigos\fileupload\FileUploadUI;
    //$this->registerJsFile("/js/weixin/jquery.min.js",['position' => \yii\web\View::POS_HEAD]);
?>
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--        //alert("Hello world!"); //测试jQuery-->
<!--    });-->
<!--</script>-->

<h3>首页轮播图片设置</h3>
<hr>
<h4>未展示轮播图</h4>

<?php //$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>


<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
    'url' => ['home/receiveimage', 'id' => $model->image],
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
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploaddestroy' => 'function(e, data) {
                                    $(this).find(tr).remove();
                                }',   //还是得添加删除按钮事件
    ],
]);
?>

<hr>
<h4>已展示轮播图</h4>
    <table role="presentation" class="table table-striped">
        <tbody class="files">
        <tr align="center">
            <td>
                轮播图预览
            </td>
            <td>
                标题
            </td>
            <td>
                链接
            </td>
            <td>
                操作
            </td>
        </tr>
<?php

foreach ($carousel as $onecarousel) {
    if(is_file($onecarousel['imgurl'])){
        $imagepath = '/'.$onecarousel['imgurl'];
?>
        <tr class="template-download fade in">
            <td>
            <span class="preview">

                    <a href="<?php echo $imagepath;?>" target="_blank" title="<?php echo $onecarousel['imgurl'];?>" data-gallery="">
                        <img src="<?php echo $imagepath;?>" title="<?php echo $onecarousel['title'];?>" height="100"/></a>

            </span>
            </td>
            <td>
                <p class="name">

                    <a href="<?php echo $onecarousel['link'];?>" title="<?php echo $onecarousel['imgurl']?>" download="<?php echo $onecarousel['imgurl']?>" data-gallery=""><?php echo $onecarousel['title'];?></a>

                </p>

            </td>
            <td>
                <span class="size"><?php echo $onecarousel['link'];?></span>
            </td>
            <td>

                <button class="btn btn-danger delete" data-type="DELETE" data-url="home/delimg">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>移除展示</span>
                </button>
                <input name="delete" value="1" class="toggle" type="checkbox">

            </td>
        </tr>
<?php }} ?>
        </tbody>
    </table>
