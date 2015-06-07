<?php
use yii\helpers\Url;
$this->title = '微商城';
?>


<div class="custom-search">
    <form action="<?=Url::toRoute(['query','id'=>$id])?>" method="GET">
        <input type="search" class="custom-search-input" placeholder="商品搜索：请输入商品关键字" name="q" value="">
        <input type="hidden" name="kdt_id" value="54023">
        <button type="submit" class="custom-search-button">搜索</button>
    </form>
</div>