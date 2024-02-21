ExpireTokenAction<?php

namespace app\controllers\app;

use yii\base\Action;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class ExpireTokenAction extends BaseAction
{
    public function run()
    {
        $postData = $this->controller->requestData;
        if (!isset($postData['token'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to send the token'
            ];
        }
        $token = $postData['token'];
        $usedResponse = AccessToken::markAsUsed($token, 'bearer_token');

        return [
            'success' => $usedResponse
        ];
    }
}