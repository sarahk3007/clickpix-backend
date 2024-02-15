<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;
use app\components\StripeSdk;

use Yii;

class CreatePaymentLinkAction extends BaseAction
{
    public function run()
    {
        $success = false;
        $postData = $this->controller->requestData;
        if (!isset($postData['ids']) || (isset($postData['ids']) && !is_array($postData['ids']))) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a price'
            ];
        }
        $countPixels = count($postData['ids']);
        $price = 100 * $countPixels;
        $stripe = new StripeSdk;
        
        $res = $stripe->createLink($price);
        if ($res->url) {
            $url = $res->url;
        }

        $ids = implode(",", $postData['ids']);
        $sql = "UPDATE `image` SET paid = true WHERE id IN (" . $ids . ")";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        if ($result) {
            return $url;
        } else {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'Please try again later'
            ];
        }
    }
}