<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class ReleasePixelsAction extends BaseAction
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
        $ids = implode(",", $postData['ids']);
        $connection = Yii::$app->getDb();
        $availableSql = "SELECT id FROM image WHERE id IN (" . $ids . ") AND (available = 0 OR paid = true)";
        $command = $connection->createCommand($availableSql);
        $availableIds = $command->queryAll();
        
        if (count($availableIds) > 0) {
            $goodIds = implode(",", array_column($availableIds, 'id'));
            $sql = "UPDATE `image` SET available = 1, paid = false WHERE id IN (" . $goodIds . ")";
            $command = $connection->createCommand($sql);
            $result = $command->execute();
            if ($result && $result > 0) {
                return [
                    'success' => true,
                    'ids' => $postData['ids']
                ];
            } else {
                Yii::$app->response->statusCode = 400;
                return [
                    'error_message' => 'Please try again later'
                ];
            }
        } else {
            return [
                'success' => true,
            ];
        }
    }
}