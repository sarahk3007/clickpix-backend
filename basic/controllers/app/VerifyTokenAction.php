<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class VerifyTokenAction extends BaseAction
{
    public function run()
    {
        $success = false;
        $postData = $this->controller->requestData;
        if (!isset($postData['phone']) || !isset($postData['code'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a valid phone and code'
            ];
        }
        $codeModel = AccessToken::validateToken($postData['code'], 'sms_token');

        if (!empty($codeModel)) {
            $usedResponse = AccessToken::markAsUsed($postData['code']);
            if ($usedResponse) { 
                $success = true;
            }
        }

        return [
            'data' => $success,
            'ids' => $postData['ids'] ?? []
        ];
    }
}