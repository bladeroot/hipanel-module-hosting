<?php

namespace hipanel\modules\hosting\models\query;

use hiqdev\hiart\ActiveQuery;

class PrefixQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->andWhere(['is_ip' => false]);
    }
}
