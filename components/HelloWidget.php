<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget{
    public $harry;

    public function init(){
        parent::init();
        if($this->harry===null){
            $this->harry= 'Welcome User';
        }else{
            $this->harry= 'Welcome '.$this->harry;
        }
    }

    public function run(){
        return Html::encode($this->harry);
    }
}
?>