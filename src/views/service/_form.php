<?php

/* @var View */
/* @var $model hipanel\modules\hosting\models\Service */
/* @var $softs array */
/* @var $states array */


echo \hipanel\modules\hosting\widgets\HostingForm::widget([
    'models' => $models,
    'scenario' => $model->isNewRecord ? $model->scenario : 'update',
    'inputs' => [
        Yii::$app->user->can('support') ? 'client' : null, 'server',
        [
            'input_name' => 'name',
        ],
        'ips',
        [
            'input_name' => 'bin',
        ],
        [
            'input_name' => 'etc',
        ],
        [
            'type' => 'dropDownList',
            'input_name' => 'soft',
            'dropDownList' => $softs,
        ],
        [
            'type' => 'dropDownList',
            'input_name' => 'state',
            'dropDownList' => $states,
        ],
    ],
]);

