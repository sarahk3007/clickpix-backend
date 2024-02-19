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
        //TODO finish the cancel payment
        $ids = Yii::$app->request->get('ids');
        $phone = Yii::$app->request->get('phone');
        $flag = Yii::$app->request->get('flag');
        $email = Yii::$app->request->get('email');
        $name = Yii::$app->request->get('name');
        
        //TODO finish the view cancel payment
        return $this->controller->render('/site/cancel');
    }
}