<?php

use hipanel\modules\hosting\widgets\HostingForm;
/* @var $this yii\web\View */
/* @var $model hipanel\modules\hosting\models\Db */
/* @var $type string */
?>

<div class="hosting-create">
    <?= HostingForm::widget([
        'models' => $models,
        'scenario' => $model->isNewRecord ? 'create' : 'update',
        'inputs' => [
            'client', 'server', 'account',
            'service_id',
            [
                'input_name' => 'name',
            ],
            'password',
            [
                'input_name' => 'description',
            ]
        ],
        'js' => null,
    ]) ?>

</div>
