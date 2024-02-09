<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;
//use Twilio\Rest\Client;


class TwilioSdk
{
    public function SendSMS($message_text, string $recepients) 
    {
        $sid = Yii::$app->params['twilio']['sid'];
        $token = Yii::$app->params['twilio']['token'];
        $from = Yii::$app->params['twilio']['from'];

        $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
        $params = [
            'To' => $recepients,
            'From' => $from,
            'Body' => $message_text
        ];

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport',
        ]);

        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($params)
            ->send();

        if ($response->isOk) {
            return $response->data;
        } else {
            return json_decode($response->content);
        }
        
    }
}