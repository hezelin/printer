<?php
//use Yii;
if( Yii::$app->user->isGuest)
    return [];
return [Yii::$app->user->identity->id => [Yii::$app->user->identity->role]];
/*return [
    2 => [
        'server',
    ],
    3 => [
        'admin',
    ],
];*/
