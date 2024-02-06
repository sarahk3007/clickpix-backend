<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;

use Yii;

class GetRandomIdsAction extends BaseAction
{
    public function run()
    {
        ini_set('memory_limit','2G');
        $postData = $this->controller->requestData;
        if (!$postData['num']) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a number of pixels'
            ];
        }
        
        $sql = "SELECT id FROM image WHERE available = 1";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        $ids = array_rand($result, $postData['num']);

        return [
            'data' => $ids
        ];


    }
}