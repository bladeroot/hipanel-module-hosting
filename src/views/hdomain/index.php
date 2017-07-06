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
                'enable-dns' => [
                ],
                'disable-dns' => [
                ],
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
