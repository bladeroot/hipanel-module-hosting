<?php

use hipanel\modules\hosting\grid\RequestGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use hipanel\widgets\BulkButtonsRender;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:hosting', 'Requests');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][]  = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->setSearchFormData(compact('objects', 'states')) ?>

        <?php $page->beginContent('main-actions') ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('show-actions') ?>
            <?= $page->renderLayoutSwitcher() ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'server', 'time', 'state',
                ],
            ]) ?>
            <?= $page->renderPerPage() ?>
        <?php $page->endContent() ?>

        <?= BulkButtonsRender::widget([
            'buttons' => [
                'close' => [
                    'button' => [
                        'label' => '<i class="fa fa-window-close-o"></i> ' . Yii::t('hipanel:hosting', 'Close request'),
                        'linkOptions' => ['data-toggle' => 'modal'],
                        'url' => '#bulk-close-modal',
                    ],
                    'modal' => [
                        'id' => 'bulk-close-modal',
                        'bulkPage' => true,
                        'header' => Html::tag('h4', Yii::t('hipanel:hosting', 'Close request'), ['class' => 'modal-title label-warning']),
                        'headerOptions' => ['class' => 'label-warning',],
                        'scenario' => 'bulk-close',
                        'actionUrl' => ['bulk-close-modal'],
                        'handleSubmit' => false,
                        'toggleButton' => false,
                    ]
                ],
                'delete' => true,
            ],
            'page' => $page,
        ]) ?>


        <?php /** $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), 'delete', 'danger') ?>
        <?php $page->endContent() **/ ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= RequestGridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $model,
                    'boxed' => false,
                    'columns'      => [
                        'checkbox', 'classes',
                        'server', 'account',
                        'actions',
                        'object', 'time', 'state',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
