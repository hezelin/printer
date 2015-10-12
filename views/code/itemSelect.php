<?php
    $this->title = '生成配件码';
?>

<?php
    $item = \app\modules\shop\models\Shop::getCategory();
?>

<?php if($item):?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>耗材类目</th>
            <th>生成数量</th>
            <th>#</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($item as $id=>$name):?>
            <tr><td><?=$name?></td><td>
            <input type="text" value="50" class="form-control item-num" />
            <input type="hidden" value="<?=$id?>" class="item-id" />
                </td><td><button type="button" class="btn btn-info btn-sm code-btn"> 生成</button></td></tr>
            <?php endforeach;?>
        </tbody>
    </table>
<?php else:?>
    <div>
        没有类目
    </div>
<?php endif;?>

<form id="item-form" method="post">
    <input name="_csrf" type="hidden" value="<?php echo \Yii::$app->request->csrfToken; ?>"/>
    <input type="hidden" name="item_id" id="item-id" />
    <input type="hidden" name="num" id="item-num" />
</form>

<script>
    <?php $this->beginBlock('JS_END') ?>
        $('.code-btn').click(function(){
            var tr = $(this).closest('tr');
            var num = parseInt( tr.find('.item-num').val() );
            var id = parseInt( tr.find('.item-id').val() );
            var ex = /^\d+$/;
            if (ex.test(num)){
                $('#item-id').val(id);
                $('#item-num').val(num);
                $('#item-form').submit();
            }else
                alert('数量必须为整数!');

        })
    <?php $this->endBlock();?>
</script>

<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>