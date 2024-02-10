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
        $messageServiceId = Yii::$app->params['twilio']['messageServiceId'];
        $twilio = new Client($sid, $token);

        // $campaign = $twilio->messaging->v1->services($messageServiceId)
        //     ->a2p->v1->alphaSender->create([
        //         'sender_id' => $messageServiceId,
        //         'country_code' => 'US',
        //         'type' => 'marketing'
        //     ]);
        // print_r($campaign->sid);die;

        $message = $twilio->messages
        ->create($recepients, // to
            [
            "from" => $from,
            "body" => $message_text,
            //"messagingServiceSid" => $messageServiceId,
            // 'applicationSid' => 'IDDApplication'
            ]
        );
        Yii::info('recipients : ' . print_r($recepients, true), 'sms');
        Yii::info('Request Data ' . print_r([
            "from" => $from,
            "body" => $message_text
            ], true), 'sms');
        Yii::error('Response ' . print_r($message, true), 'sms');
        return $message;
        
    }
}