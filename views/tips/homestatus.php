<?php
use yii\helpers\Html;
$this->title = '状态提示';

/*
 * 参数  $tips ，提示文件
 * $btnText 按钮文字
 * $btnUrl 按钮链接
 */
?>

<div class="h-hint"><?=$tips?></div>

<?php
if(isset($btnText,$btnUrl) && $btnText && $btnUrl) {
    echo Html::a($btnText, $btnUrl, ['class' => 'h-button']);
}
echo '<p>&nbsp;</p>';
if(isset($btnText2,$btnUrl2) && $btnText2 && $btnUrl2) {
    echo Html::a($btnText2, $btnUrl2, ['class' => 'h-button']);
}
?>
