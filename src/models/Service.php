<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\modules\hosting\models;

use Yii;

class Service extends \hipanel\base\Model
{

    use \hipanel\base\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'server_id', 'device_id', 'client_id', 'seller_id', 'soft_id'], 'integer'],
            [['name', 'server', 'device', 'client', 'seller', 'soft'], 'safe'],
            [['ips', 'bin', 'etc'], 'safe'],
            [['soft_type', 'soft_type_label', 'state', 'state_label'], 'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->mergeAttributeLabels([
            'soft_type'       => Yii::t('app', 'Soft Type'),
            'soft_type_label' => Yii::t('app', 'Soft type label'),
        ]);
    }

    public function getIps() {
        return $this->hasMany(Ip::className(), ['service_id', 'id']);
    }
}
