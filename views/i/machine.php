<?php
use yii\helpers\Url;
$this->title = '我的机器';
?>


<?php if($model):?>
<pre>
    <?php print_r($model);?>
</pre>
 <?php else:?>
 <div class="hint">亲，您还没有机器，赶快去租借一台吧</div>
    <a class="button" href="<?= Url::toRoute(['/rent/list','id'=>$id])?>">租借机器</a>

<?php endif;?>