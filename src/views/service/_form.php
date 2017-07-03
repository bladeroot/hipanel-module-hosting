<?php

/* @var View */
/* @var $model hipanel\modules\hosting\models\Service */
/* @var $softs array */
/* @var $states array */


echo \hipanel\modules\hosting\widgets\HostingForm::widget([
    'models' => $models,
    'scenario' => $model->isNewRecord ? $model->scenario : 'update',
    'inputs' => [
        'client', 'server',
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
            'input_name' => 'soft',
            'dropDownList' => $softs,
        ],
        [
            'input_name' => 'state',
            'dropDownList' => $states,
        ],
    ],
]);

