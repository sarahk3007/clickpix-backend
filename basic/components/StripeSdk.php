<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;

class StripeSdk
{
  public function CreateLink($price) 
  {
      $token = Yii::$app->params['stripe']['token'];
      $secretKey = Yii::$app->params['stripe']['secretKey'];

      $stripe = new \Stripe\StripeClient($secretKey);
      try {
        $paymentLink = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'pixels',
                    ],
                    'unit_amount' => $price, // Amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://clickpix-backend-u11765.vm.elestio.app/success.html',
            'cancel_url' => 'https://clickpix-backend-u11765.vm.elestio.app/cancel.html',
        ]);

        return $paymentLink;
  
      } catch (\Stripe\Exception\ApiErrorException $e) {
          echo 'Error: ' . $e->getMessage();
      }
  }
}