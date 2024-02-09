<?php

namespace app\components;

use Yii;
use Twilio\Rest\Client;

class TwilioSdk
{
    public function SendSMS($message_text, string $recepients) 
    {
        $sid = Yii::$app->params['twilio']['sid'];
        $token = Yii::$app->params['twilio']['token'];
        $from = Yii::$app->params['twilio']['from'];
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
        ->create($recepients, // to
            [
            "from" => $from,
            "body" => $message_text
            ]
        );
        
    }
}