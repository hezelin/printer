<?php
    use dosamigos\fileupload\FileUploadUI;
    use yii\bootstrap\Alert;
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
<?=Alert::widget([
    'options' => [
        'class' => 'alert-info',
        'style' => 'color:gray; font-size: 12px',
    ],
    'body' => '<strong>温馨提示</strong>：为保证显示质量，请上传长宽比例为2:1的图片。',
    ])
?>
<!--上传图片插件-->
<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
//    'url' => ['home/receiveimage', 'weixinid' => $_GET['id']],
    'url' => Url::toRoute(['home/receiveimage', 'weixinid' => $wx_id]),
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
                        <img src="<?php echo $imagepath;?>" title="<?php echo $onecarousel['title'];?>" style="max-height: 100px"/></a>

            </span>
            </td>
            <td>
                <p class="name">

                    <span class="textedit" data-tdtype="edit" data-id="<?=$onecarousel['id'] ?>" data-field="title" data-require="1" data-unique="0"><?php echo trim($onecarousel['title'])?$onecarousel['title']:"点击设置";?></span>

                </p>

            </td>
            <td>
                <span class="textedit" data-tdtype="edit" data-id="<?=$onecarousel['id'] ?>" data-field="link" data-unique="0"><?php echo trim($onecarousel['link'])?$onecarousel['link']:"点击设置";?></span>
            </td>
            <td>

                <button class="btn btn-danger deletebtn" data-id="<?=$onecarousel['id'] ?>" data-type="DELETE">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>删除</span>
                </button>
                <input name="delete" value="<?=$onecarousel['id'] ?>" class="toggle" type="checkbox">

            </td>
        </tr>
<?php }} ?>
        </tbody>
    </table>


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