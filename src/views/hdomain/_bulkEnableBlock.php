<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'enable-block',
    'affectedObjects' => Yii::t('hipanel:hosting', 'Affected hosting domains'),
    'formatterField' => 'domain',
    'hiddenInputs' => ['id', 'domain'],
    'visibleInputs' => ['comment'],
    'submitButton' => Yii::t('hipanel', 'Enable block'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
    'dropDownInputs' => ['type' => $blockReasons ],
]);

