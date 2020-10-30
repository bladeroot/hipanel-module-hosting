<?php

use hipanel\widgets\AdvancedSearch;
use yii\web\View;

/**
 * @var AdvancedSearch $search
 * @var $this View
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->dropDownList($this->context->getRefs('type,ip_prefix', 'hipanel.hosting.ipam'), ['prompt' => '---']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('vrf')->dropDownList($this->context->getRefs('type,ip_vrf', 'hipanel.hosting.ipam'), ['prompt' => '---']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('role')->dropDownList($this->context->getRefs('type,ip_prefix_role', 'hipanel.hosting.ipam'), ['prompt' => '---']) ?>
</div>
