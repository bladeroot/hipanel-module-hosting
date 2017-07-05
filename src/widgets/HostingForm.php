<?php

namespace hipanel\modules\hosting\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use Yii;

class HostingForm extends Widget
{

    /**
     * @var Model
     */
    public $models;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $scenario;
    public $js;

    /**
     * @var boolean
     */
    public $isNewRecord;

    /**
     * @var array
     */
    public $inputs;
    protected $fields = [];

    /**
     * var object [[ActiveForm]]
     */
    protected $form;

    public function init()
    {
        parent::init();

        if ($this->models === null) {
            throw new InvalidConfigException('Please specify the "models" property.');
        }

        if ($this->scenario === null) {
            throw new InvalidConfigException('Please specify the "scenario" property.');
        }

        if ($this->inputs === null) {
            throw new InvalidConfigException('Please specify the "inputs" property.');
        }

        $defaultInputs = $this->getDefaultInputs();

        foreach ($this->inputs as $id => $name) {
            if ($name === null) {
                continue;
            }

            if (is_string($name)) {
                $this->fields[$name] = $defaultInputs[$name];
            }

            if (is_array($name)) {
                $this->fields[$name['input_name']] = $name;
            }
        }
    }

    public function run()
    {
        $this->beginForm();
        $this->renderForm();
        $this->endForm();
        if ($this->js !== null) {
            $this->getView()->registerJs($this->js);
        }
    }

    protected function beginForm()
    {
        echo Html::beginTag('div', ['class' => 'hosting-create']);
            $this->form = ActiveForm::begin([
                'id' => "{$this->scenario}-dynamic-form",
                'enableClientValidation' => true,
                'validateOnBlur' => true,
                'enableAjaxValidation' => true,
                'validationUrl' => Url::toRoute([
                    'validate-form',
                    'scenario' => $this->scenario
                ]),
            ]);
                echo Html::beginTag('div', ['class' => 'container-items']);
    }

    protected function renderForm()
    {
        foreach ($this->models as $i => $model) {
            if ($this->scenario === 'update') {
                $i = $model->id;
            }

            echo Html::beginTag('div', ['class' => 'row']);
                echo Html::beginTag('div', ['class' => 'col-md-12']);
                    echo Html::beginTag('div', ['class' => 'box box-danger']);
                         echo Html::beginTag('div', ['class' => 'box-body']);
                            echo Html::beginTag('div', [
                                'class' => 'form-instance',
                                'xmlns' => 'http://www.w3.org/1999/html',
                            ]);
                            if ($this->scenario === 'update') {
                                echo Html::activeHiddenInput($model, "[$i]id");
                            }
                            $this->renderFields($model, $i);
                            echo Html::endTag('div');
                         echo Html::endTag('div');
                    echo Html::endTag('div');
                echo Html::endTag('div');
            echo Html::endTag('div');
        }
    }

    protected function renderFields($model, $i)
    {
        foreach ($this->fields as $field => $values) {
            echo $this->renderField($model, $this->form->field($model, "[{$i}]{$field}"), $field, $values, $i);
        }
    }

    protected function renderField($model, $input, $field, $values, $i)
    {
        switch ($values['type']) {
            case 'widget':
                return $this->renderWidgetField($model, $input, $field, $values, $i);
                break;
            case 'input':
                $input = $input->input($values['options']['type'], [
                    'data' => $options['data'] ? : [],
                    'value' => $options['value'],
                ]);
                break;
            case 'dropDownList':
                $input = $input->dropDownList($values['dropDownList']);
                break;
        }
        if (array_key_exists('hint', $values)) {
            $input = $input->hint($options['hint']);
        }

        if ($this->scenario === 'update' && $values['options']['readonly'] === true) {
            $input = $input->textInput(['readonly' => true]);
        }

        return $input;
    }

    protected function renderWidgetField($model, $input, $field, $values, $i)
    {
        $widgetOptions = [
            'id' => "{$this->scenario}-{$i}-{$field}-input",
            'inputOptions' => [
                'readonly' => $this->scenario === 'update' && $values['readonly'] === true,
            ],
        ];

        $widgetOptions = $values['options']
            ? ArrayHelper::merge($values['options'], $widgetOptions)
            : $widgetOptions;

        foreach ($widgetOptions as $name => $value) {
            if (is_callable($value)) {
                $widgetOptions[$name] = $value($model);
            }
        }

        return $input->widget($values['class'], $widgetOptions);
    }

    protected function endForm()
    {
                    echo Html::beginTag('div', ['class' => 'row']);
                        echo Html::beginTag('div', ['class' => 'col-md-12']);
                            echo Html::beginTag('div', ['class' => 'box box-widget']);
                                echo Html::beginTag('div', ['class' => 'box-body']);
                                    echo  Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']);
                                echo Html::endTag('div');
                            echo Html::endTag('div');
                        echo Html::endTag('div');
                    echo Html::endTag('div');
                echo Html::endTag('div');
            ActiveForm::end();
        echo Html::endTag('div');
    }

    protected function getDefaultInputs()
    {
        return [
            'client' => [
                'type' => 'widget',
                'class' => \hipanel\modules\client\widgets\combo\ClientCombo::class,
                'readonly' => true,
            ],
            'server' => [
                'type' => 'widget',
                'class' => \hipanel\modules\server\widgets\combo\PanelServerCombo::class,
                'options' => [
                    'state' => \hipanel\modules\server\models\Server::STATE_OK,
                ],
                'readonly' => true,
            ],
            'account' => [
                'type' => 'widget',
                'class' => \hipanel\modules\hosting\widgets\combo\SshAccountCombo::class,
                'readonly' => true,
            ],
            'service_id' => [
                'type' => 'widget',
                'class' => \hipanel\modules\hosting\widgets\combo\DbServiceCombo::class,
                'readonly' => true,
            ],
            'ips' => [
                'type' => 'widget',
                'class' => \hipanel\modules\hosting\widgets\ip\ServiceIpCombo::class,
                'options' => [
                    'current' => function ($model) {
                        $ips = array_unique(array_merge((array)$model->ip, (array)$model->ips));
                        return array_combine($ips, $ips);
                    },
                ],
            ],
            'password' => [
                'type' => 'widget',
                'class' => \hipanel\widgets\PasswordInput::class,
                'readonly' => false,
            ],
            'path' => [
                'type' => 'widget',
                'class' => \hipanel\modules\hosting\widgets\combo\AccountPathCombo::class,
            ],
            'sshftp_ips' => [
                'type' => 'input',
                'hint' => Yii::t('hipanel:hosting', 'Access to the account is opened by default. Please input the IPs, for which the access to the server will be granted'),
                'options' => [
                    'type' => 'text',
                    'data' => [
                        'value' => Yii::t('hipanel:hosting', 'IP restrictions'),
                        'content' => Yii::t('hipanel:hosting', 'Text about IP restrictions'),
                    ],
                    'value' => function ($model){
                        return method_exists($model, 'getSshFtpIpsList') ? $model->getSshFtpIpsList() : '';
                    },
                ],
            ],
        ];
    }
}

