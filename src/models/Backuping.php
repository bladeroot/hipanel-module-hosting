<?php
/**
 * Hosting Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-hosting
 * @package   hipanel-module-hosting
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\hosting\models;

use hipanel\models\Obj;
use hipanel\models\Ref;
use Yii;
use yii\helpers\ArrayHelper;

class Backuping extends \hipanel\base\Model
{
    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['id', 'service_id', 'server_id', 'account_id', 'client_id', 'seller_id'], 'integer'],
            [['client', 'seller'], 'safe'],
            [['day', 'hour', 'path', 'include', 'exclude'], 'safe'],
            [['method', 'method_label', 'server', 'account', 'name', 'object', 'service'], 'safe'],
            [['backup_last'], 'date'],
            [['backup_count', 'total_du', 'total_du_gb'], 'integer'],
            [['type', 'type_label', 'state', 'state_label'], 'safe'],

            [['id', 'type'], 'safe', 'on' => ['update']],
            [['id'], 'integer', 'on' => ['delete', 'disable', 'enable']],

            // Update settings
            [['id', 'service_id'], 'integer', 'on' => 'update'],
            [['skip_lock'], 'boolean', 'on' => 'update'],
            [['day', 'hour'], 'integer', 'on' => 'update'],
            [['day', 'hour'], 'default', 'value' => 0, 'on' => 'update'],
            [['path', 'include', 'exclude', 'type', 'method'], 'string', 'on' => 'update'],
        ];
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'day' => Yii::t('hipanel', 'Date'),
            'hour' => Yii::t('hipanel', 'Time'),
            'backup_last' => Yii::t('hipanel:hosting', 'Last backup'),
            'backup_count' => Yii::t('hipanel', 'Count'),
            'total_du' => Yii::t('hipanel:hosting', 'Disk usage'),
            'total_du_gb' => Yii::t('hipanel:hosting', 'Disk'),
            'method_label' => Yii::t('hipanel:hosting', 'Method'),
            'method_id' => Yii::t('hipanel:hosting', 'Method'),
            'account_id' => Yii::t('hipanel', 'Account'),
            'server_id' => Yii::t('hipanel', 'Server'),
            'state_label' => Yii::t('hipanel', 'State'),
            'state' => Yii::t('hipanel', 'State'),
            'type' => Yii::t('hipanel:hosting', 'Periodicity'),
            'object' => Yii::t('hipanel:hosting', 'Object'),
            'skip_lock' => Yii::t('hipanel:hosting', 'Skip Lock'),
        ]);
    }

    public function getObj()
    {
        return Yii::createObject([
            'class' => Obj::class,
            'id' => $this->id,
            'name' => $this->name,
            'class_name' => $this->object,
        ]);
    }

    public function getTypeOptions(): array
    {
        return Yii::$app->get('cache')->getOrSet([__METHOD__], function () {
            return ArrayHelper::map(Ref::find()->where([
                'gtype' => 'type,backuping', 'select' => 'full',
            ])->all(), 'name', function ($model) {
                return Yii::t('hipanel.hosting.backuping.periodicity', $model->name);
            });
        }, 86400 * 24); // 24 days
    }
}
