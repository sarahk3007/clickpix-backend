<?php

namespace app\controllers\app;

use yii\base\Action;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class ExpireTokenAction extends BaseAction
{
    public function run()
    {
        $headers = Yii::$app->request->headers;
        if ($headers->has('Authorization')) {
            $authHeader = $headers->get('Authorization');
            if (preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches)) {
                $bearerToken = $matches[1];
                $usedResponse = AccessToken::markAsUsed($bearerToken, 'bearer_token');

                return [
                    'success' => $usedResponse
                ];
            }   
        }

        Yii::$app->response->statusCode = 400;
        return [
            'error_message' => 'You have to send the token'
        ];
    }
}