<?php
use app\components\PassthroughWidget;
use yii\helpers\Url;
$this->title = '配件列表';
?>

<?php
    echo PassthroughWidget::widget([
        'data'=>[
            [
                'name'=>'全部',
                'key'=>'all',
                'active'=>true
            ],
            [
                'name'=>'A4纸张',
                'key'=>'2',
                'active'=>false
            ],
            [
                'name'=>'晒鼓',
                'key'=>'2',
                'active'=>false
            ],
            [
                'name'=>'墨盒',
                'key'=>'2'
            ],
        ],
        'action'=>Url::toRoute('/shop/parts/list'),
    ]);



?>
