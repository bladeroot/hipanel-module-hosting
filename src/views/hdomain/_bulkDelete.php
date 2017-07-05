<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'delete',
    'affectedObjects' => Yii::t('hipanel:hosting', 'Affected hosting domains'),
    'formatterField' => 'domain',
    'hiddenInputs' => ['id', 'domain'],
    'bodyWarning' => Yii::t('hipanel:server', 'This action is irreversible and causes full data loss including backups!'),
    'submitButton' => Yii::t('hipanel', 'Delete'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);

