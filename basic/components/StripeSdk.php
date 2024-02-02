<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;

class TwilioSdk
{
    public function CreateLink($price) 
    {
        $token = Yii::$app->params['stripe']['token'];
        $stripe = new \Stripe\StripeClient($token);
        
        $stripe->prices->create([
            'product' => 'pixels',
            'unit_amount' => $price,
            'currency' => 'usd',
        ]);

        //return the price id
        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => [[
              # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              'price' => '{{PRICE_ID}}',//from prices create
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'return_url' => '',//TODO
            'automatic_tax' => [
              'enabled' => false,
            ],
        ]);
    }
}