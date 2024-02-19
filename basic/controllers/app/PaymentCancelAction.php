<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;

use Yii;

class PaymentCancelAction extends BaseAction
{
    public function run()
    {
        
        return $this->controller->render('/site/cancel');
    }
}