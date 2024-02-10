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
        $postData = $this->controller->requestData;
        if (!$postData['ids'] || ($postData['ids'] && !is_array($postData['ids']))) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a price'
            ];
        }
        $countPixels = count($postData['ids']);
        $price = 1 * $countPixels;
        $stripe = new StripeSdk;
        $res = $stripe->createLink($price);
        if ($res->url) {
            $url = $res->url;
        }

        $ids = implode(",", $postData['ids']);
        $sql = "UPDATE `image` SET paid = true, available = false WHERE id IN (" . $ids . ")";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        if ($result) {

        }
    }
}