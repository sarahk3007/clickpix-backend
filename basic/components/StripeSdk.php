<?php

namespace app\components;

use Yii;
use yii\httpclient\Client;

class StripeSdk
{
    public function createLink($price, $name, $flag, $email) 
    {
        $token = Yii::$app->params['stripe']['token'];
        $secretKey = Yii::$app->params['stripe']['secretKey'];

        $stripe = new \Stripe\StripeClient($secretKey);
        try {

            $additionalParams = [
                'flag' => $flag,
                'email' => $email,
                'name' => $name
            ];

            if ($flag) {
                $which = 'israelian';
            } else {
                $which = 'palestinian';
            }
            
            $successUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/payment-success']);
            $cancelUrl = Yii::$app->urlManager->createAbsoluteUrl(['site/payment-cancel']);
            $successUrl .= '?' . http_build_query($additionalParams);
            $cancelUrl .= '?' . http_build_query($additionalParams);
            $successUrl .= '&session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl .= '&session_id={CHECKOUT_SESSION_ID}';
            
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Your ' . $which . ' pixels',
                        ],
                        'unit_amount' => $price, // Amount in cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'payment_intent_data' => [
                    'setup_future_usage' => 'off_session',
                ],
                'expires_at' => time() + (60 * 30),
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]);

            return $session;
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}