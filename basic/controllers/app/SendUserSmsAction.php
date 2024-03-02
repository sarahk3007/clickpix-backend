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
        if (!isset($postData['email'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a valid email'
            ];
        }
        $mail = $postData['email'];

        $usedResponse = AccessToken::markAsUsed(null, 'sms_token', $mail);
        $token = AccessToken::create(1, 'sms_token', $mail);
        $msg = 'Your Click&Pix verification code is: ' . $token;
        
        try {
            //TODO design email verification
            $message = Yii::$app->mailer->compose(['html' => '@app/views/layouts/verification'],['content'=>$msg])
                ->setFrom(['noreply@clickandpix.com' => 'Click and Pix system'])
                ->setSubject('Your verification code to ClickAndPix Website')
                ->setTo($mail)
                ->send();
            $success = true;
            return [
                'data' => $success,
                'ids' => $postData['ids'] ?? []
            ];
        } catch (\Throwable $error) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'The message could not be sent'
            ];
        }
    }
}