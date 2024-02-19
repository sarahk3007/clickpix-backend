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
        if (!isset($postData['ids']) || (isset($postData['ids']) && !is_array($postData['ids'])) || !isset($postData['phone']) || !isset($postData['name']) || !isset($postData['flag']) || !isset($postData['email'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter all POST parameters'
            ];
        }

        $connection = Yii::$app->getDb();
        $ids = implode(",", $postData['ids']);
        $availableSql = "SELECT id, available FROM image WHERE id IN (" . $ids . ") AND available = 1";
        $command = $connection->createCommand($availableSql);
        $availableIds = $command->queryAll();
        if (count($availableIds) < count($postData['ids'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'The pixels are no longer available, Please try again'
            ];
        }

        $countPixels = count($postData['ids']);
        $price = 100 * $countPixels;
        $phone = $postData['phone'];
        $flag = $postData['flag'];
        $email = $postData['email'];
        $name = $postData['name'];
        $email = 100 * $countPixels;
        $stripe = new StripeSdk;
        
        $res = $stripe->createLink($price, $postData['ids'], $name, $phone, $flag, $email);
        
        if ($res->url) {
            $url = $res->url;
        }

        $sql = "UPDATE `image` SET paid = true WHERE id IN (" . $ids . ")";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        if ($result) {
            return $url;
        } else {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => $result,
                'error2' => $res
            ];
        }
    }
}