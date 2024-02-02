<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class CreatePaymentLinkAction extends BaseAction
{
    public function run()
    {
        $success = false;
        $postData =  Yii::$app->request->bodyParams;
        if (!$postData['ids']) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a valid phone and code'
            ];
        }
        //TODO create payment url
        $ids = implode(",", $postData['ids']);
        $sql = "UPDATE `image` SET paid = true, available = false WHERE id IN (" . $ids . ")";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        if ($result) {

        }
    }
}