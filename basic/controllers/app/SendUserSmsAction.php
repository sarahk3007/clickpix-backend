<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\components\TwilioSdk;
use app\controllers\app\BaseAction;
use app\models\AccessToken;
use Twilio\Exceptions\RestException;

use Yii;

class SendUserSmsAction extends BaseAction
{
    public function run()
    {
        $postData = $this->controller->requestData;
        $success = false;
        if (!isset($postData['phone'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a valid phone'
            ];
        }

        $token = AccessToken::create(1, 'sms_token');
        $msg = 'Your Click&Pix verification code is: ' . $token;
        //TODO finish the email
        // $message = Yii::$app->mailer->compose(['html' => '@app/views/layouts/test'],['content'=>$msg])
        //     ->setFrom(['noreply@clickandpix.com' => 'Click and Pix system'])
        //     ->setTo(["rebeceva@gmail.com"])
        //     ->setSubject('test');

        $twilio = new TwilioSdk;
        try {
            $response = $twilio->SendSMS($msg, $postData['phone']);
            $success = true;
            return ['data' => $success];
        } catch (\Throwable $error) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'The message could not be sent'
            ];
        }
    }
}