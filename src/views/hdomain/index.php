<?php

use hipanel\modules\hosting\grid\HdomainGridView;
use hipanel\widgets\BulkButtonsRender;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\helpers\Html;

$this->title = Yii::t('hipanel', 'Domains');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?= $page->setSearchFormData(compact(['stateData', 'typeData'])) ?>

        <?php $page->beginContent('main-actions') ?>
            <div class="dropdown">
                <a class="btn btn-sm btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?= Yii::t('hipanel:hosting', 'Create domain') ?>
                    <span class="caret"></span>
                </a>
                <?= Dropdown::widget([
                    'items' => [
                        ['label' => Yii::t('hipanel:hosting', 'Create domain'), 'url' => ['create']],
                        ['label' => Yii::t('hipanel:hosting', 'Create alias'), 'url' => ['create-alias']],
                    ]
                ]) ?>
            </div>
        <?php $page->endContent() ?>

        <?php $page->beginContent('show-actions') ?>
            <?= $page->renderLayoutSwitcher() ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'domain', 'client', 'seller',
                    'account', 'server', 'state',
                ],
            ]) ?>
            <?= $page->renderPerPage() ?>
        <?php $page->endContent() ?>

        <?= BulkButtonsRender::widget([
            'buttons' => [
                'enable-block' => [
                    'button' => [
                        'visible' => Yii::$app->user->can('support'),
                    ],
                    'modal' => [
                        'header' => Html::tag('h4', Yii::t('hipanel:hosting', 'Block domains'), ['class' => 'modal-title label-warning']),
                    ],
                ],
                'disable-block' => [
                    'button' => [
                        'visible' => Yii::$app->user->can('support'),
                    ],
                    'modal' => [
                        'header' => Html::tag('h4', Yii::t('hipanel:hosting', 'Unblock domains'), ['class' => 'modal-title label-warning']),
                    ],
                ],
                'delete' => true,
            ],
            'page' => $page,
        ]) ?>

        <?php /*** $page->beginContent('bulk-actions') ?>
            <?php if (Yii::$app->user->can('support')) : ?>
                <div class="dropdown" style="display: inline-block">
                    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= Yii::t('hipanel', 'Block') ?>
                        <span class="caret"></span>
                    </button>
                    <?= Dropdown::widget([
                        'encodeLabels' => false,
                        'options' => ['class' => 'pull-right'],
                        'items' => [
                            [
                                'label' => '<i class="fa fa-toggle-on"></i> ' . Yii::t('hipanel', 'Enable'),
                                'linkOptions' => ['data-toggle' => 'modal'],
                                'url' => '#bulk-enable-block-modal',
                            ],
                            [
                                'label' => '<i class="fa fa-toggle-off"></i> ' . Yii::t('hipanel', 'Disable'),
                                'url' => '#bulk-disable-block-modal',
                                'linkOptions' => ['data-toggle' => 'modal'],
                            ],
                        ],
                    ]) ?>
                    <div>
                        <?= AjaxModal::widget([
                            'id' => 'bulk-enable-block-modal',
                            'bulkPage' => true,
                            'header'=> Html::tag('h4', Yii::t('hipanel:hosting', 'Block domains'), ['class' => 'modal-title']),
                            'scenario' => 'bulk-enable-block',
                            'actionUrl' => ['bulk-enable-block-modal'],
                            'size' => AjaxModal::SIZE_LARGE,
                            'handleSubmit' => false,
                            'toggleButton' => false,
                        ]) ?>
                        <?= AjaxModal::widget([
                            'id' => 'bulk-disable-block-modal',
                            'bulkPage' => true,
                            'header'=> Html::tag('h4', Yii::t('hipanel:hosting', 'Unblock domains'), ['class' => 'modal-title']),
                            'scenario' => 'bulk-disable-block',
                            'actionUrl' => ['bulk-disable-block-modal'],
                            'size' => AjaxModal::SIZE_LARGE,
                            'handleSubmit' => false,
                            'toggleButton' => false,
                        ]) ?>
                    </div>
                </div>
            <?php endif ?>
            <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), 'delete', 'danger')?>
        <?php $page->endContent() **/ ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= HdomainGridView::widget([
                    'boxed' => false,
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $model,
                    'columns'      => [
                        'checkbox',
                        'hdomain_with_aliases',
                        'client', 'seller', 'account', 'server',
                        'state', 'ip', 'service',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
