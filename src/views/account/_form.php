<?php

/* @var View */
/* @var $model hipanel\modules\hosting\models\Account */
/* @var $type string */

use hipanel\modules\hosting\widgets\HostingForm;

echo HostingForm::widget([
    'models' => $models,
    'model' => $model,
    'scenario' => $model->isNewRecord ? $model->scenario : 'update',
    'inputs' => [
        Yii::$app->user->can('support') ? 'client' : null,
        'server',
        $model->scenario === 'create-ftponly' ? 'account' : null,
        [
            'input_name' => 'login',
        ],
        'password',
        $model->scenario === 'create-ftponly' ? 'path' : null,
        'sshftp_ips'
    ],
    'js' => "$('#account-sshftp_ips').popover({placement: 'top', trigger: 'focus'});",
]);

