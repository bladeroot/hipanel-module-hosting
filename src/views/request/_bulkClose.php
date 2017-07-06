<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'enable-block',
    'affectedObjects' => Yii::t('hipanel:hosting', 'Affected requests'),
    'formatterField' => 'object_name',
    'hiddenInputs' => ['id'],
    'visibleInputs' => ['error_code'],
    'submitButton' => Yii::t('hipanel', 'Close'),
    'submitButtonOptions' => ['class' => 'btn btn-warning'],
]);

