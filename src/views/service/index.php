<?php

/*
 * Client Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-hosting
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */

use hipanel\modules\hosting\grid\ServiceGridView;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:hosting', 'Services');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][]  = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->setSearchFormData(compact(['stateData', 'softData'])) ?>
        <?php $page->beginContent('main-actions') ?>
            <?php if (Yii::$app->user->can('admin')) : ?>
                <?= Html::a(Yii::t('hipanel:hosting', 'Create service'), '#create-modal', [
                    'class' => 'btn btn-sm btn-success',
                    'data-toggle' => 'modal',
                ]) ?>
            <?php endif ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('show-actions') ?>
            <?= $page->renderLayoutSwitcher() ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'client', 'seller',
                    'name', 'soft',
                ],
            ]) ?>
            <?= $page->renderPerPage() ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
        <?php $page->endContent() ?>
        <?php if (Yii::$app->user->can('admin')) : ?>
            <div class="text-left">
                <?= AjaxModal::widget([
                    'id' => 'create-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:hosting', 'Create service'), ['class' => 'modal-title']),
                    'headerOptions' => ['class' => 'label-info'],
                    'scenario' => 'create',
                    'actionUrl' => ['create'],
                    'handleSubmit' => false,
                    'toggleButton' => false,
                    'options' => [
                        'tabindex' => false,
                    ],
                ]) ?>
            </div>

        <?php endif ?>


        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= ServiceGridView::widget([
                    'dataProvider' => $dataProvider,
                    'boxed' => false,
                    'filterModel' => $model,
                    'columns' => [
                        'seller_id', 'client_id',
                        'server',
                        'actions',
                        'object', 'ip',
                        'soft', 'state',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
