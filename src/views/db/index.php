<?php

use hipanel\modules\hosting\grid\DbGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel', 'Databases');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->setSearchFormData(compact(['stateData'])) ?>

        <?php $page->beginContent('main-actions') ?>
            <?= Html::a(Yii::t('hipanel:hosting', 'Create DB'), '#create-modal', [
                    'class' => 'btn btn-sm btn-success',
                    'data-toggle' => 'modal',
            ]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('show-actions') ?>
            <?= $page->renderLayoutSwitcher() ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'client', 'seller', 'account', 'server',
                    'name', 'description', 'state'
                ],
            ]) ?>
            <?= $page->renderPerPage() ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), 'delete', 'danger')?>
        <?php $page->endContent() ?>
            <div class="text-left">
                <?= AjaxModal::widget([
                    'id' => 'create-modal',
                    'bulkPage' => true,
                    'header' => Html::tag('h4', Yii::t('hipanel:hosting', 'Create DB'), ['class' => 'modal-title']),
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


        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= DbGridView::widget([
                    'boxed' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $model,
                    'columns'      => [
                        'checkbox',
                        'name', 'account', 'server',
                        'client_id', 'seller_id',
                        'description', 'state',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
