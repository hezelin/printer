<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/11
 * Time: 16:17
 */
use yii\bootstrap\Modal;
Modal::begin([
    'header' => '图片库', //不同用户应不同
    'toggleButton' => ['label' => '从图片库选择','class'=>'btn btn-default','id'=>'showmodal'],
]);
?>
<div id="content"></div>

<?php
Modal::end();
?>



    <script>
        <?php $this->beginBlock('JS_END') ?>
        $(function(){
            $('#showmodal').on('click',function(){
                $('#content').html('Loading...');
                $.ajax({
                    type: 'POST',
                    url : 'getimage',
                    success:function(data){
                        alert(data);
                        $('#content').html(data.imgurl);//空??
                    }
                });
            });

        });
        <?php $this->endBlock();?>
    </script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>