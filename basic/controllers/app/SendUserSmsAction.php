<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\components\TwilioSdk;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class SendUserSmsAction extends BaseAction
{
    public function run()
    {
        $postData = $this->controller->requestData;
        $success = false;
        if (!$postData['phone']) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a valid phone'
            ];
        }

        $token = AccessToken::create(1, 'sms_token');
        $msg = 'Your Click&Pix verification code is: ' . $token;
        $twilio = new TwilioSdk;
        $res = $twilio->SendSMS($msg, $postData['phone']);

        if ($message->id) {
            $success = true;
            return $message->id;
        }

        return 'The message could not be sent';

    }
}