<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class BlockPixelsAction extends BaseAction
{
    public function run()
    {
        $success = false;
        $postData = $this->controller->requestData;
        if (!isset($postData['ids'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter an array of ids'
            ];
        }

        $countPixels = count($postData['ids']);
        // $rest = $countPixels % 10;
        // if ($rest != 0) {
        //     $miss = 10 - $rest;
        //     Yii::$app->response->statusCode = 400;
        //     return [
        //         'error_message' => 'You need to choose ' . $miss . ' more pixels'
        //     ];
        // }
        $ids = implode(",", $postData['ids']);

        $connection = Yii::$app->getDb();
        $sql = "UPDATE `image` SET available = 0 WHERE id IN (" . $ids . ")";
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        if ($result) {
            return true;
        } else {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'Please try again later'
            ];
        }
    }
}