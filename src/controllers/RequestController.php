<?php
/**
 * Hosting Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-hosting
 * @package   hipanel-module-hosting
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @see    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\hosting\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\ViewAction;
use Yii;

class RequestController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'findOptions' => [
                    'object_class_ni' => 'domain',
                ],
                'data' => function ($action) {
                    return [
                        'objects' => $this->getObjects(),
                        'states' => $this->getFilteredStates(),
                    ];
                },
                'filterStorageMap' => [
                    'state' => 'hosting.request.state',
                    'server' => 'server.server.name',
                    'account' => 'hosting.account.login',
                    'client_id' => 'client.client.id',
                ],
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => [
                    'object_class_ni' => 'domain',
                ],
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel', 'Deleted'),
                'error' => Yii::t('hipanel:hosting', 'An error occurred when trying to delete request.'),
            ],
            'bulk-delete' => [
                'class' => SmartDeleteAction::class,
                'scenario' => 'delete',
                'success' => Yii::t('hipanel', 'Deleted'),
                'error' => Yii::t('hipanel:hosting', 'An error occurred when trying to delete requests.'),
            ],
            'bulk-delete-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_bulkDelete',
            ],
            'close' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:hosting', 'Closed'),
                'error'  => Yii::t('hipanel:hosting', 'An error occurred when trying to close request.'),
            ],
            'bulk-close' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'close',
                'success' => Yii::t('hipanel:hosting', 'Closed'),
                'error'  => Yii::t('hipanel:hosting', 'An error occurred when trying to close requests.'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $comment = Yii::$app->request->post('error_code');
                    if (!empty($type)) {
                        foreach ($action->collection->models as $model) {
                            $model->setAttributes([
                                'error_code' => $error_code,
                            ]);
                        }
                    }
                },
            ],
           'bulk-close-modal' => [
                'class' => PrepareBulkAction::class,
                'view' => '_bulkClose',
            ],
        ];
    }

    public function getFilteredStates()
    {
        $result = $this->getStates();
        unset($result['done']);

        return $result;
    }

    public function getStates()
    {
        return $this->getRefs('state,request', 'hipanel:hosting');
    }

    public function getObjects()
    {
        return [
            'db'        => Yii::t('hipanel:hosting', 'Database'),
            'hdomain'   => Yii::t('hipanel:hosting', 'Domain'),
            'device'    => Yii::t('hipanel:hosting', 'Server'),
            'service'   => Yii::t('hipanel:hosting', 'Service'),
        ];
    }
}
