<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\components\TwilioSdk;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 */
class ReleasePixelsController extends Controller
{
    public function actionIndex()
    {
        $twilio = new TwilioSdk;
        $response = $twilio->SendSMS('test release', '+972547488988');

        // $connection = Yii::$app->getDb();

        // $paymentSql = "SELECT * FROM payment_history WHERE start_date IS NOT NULL AND end_date IS NULL";
        // $command = $connection->createCommand($paymentSql);
        // $paymentHistories = $command->queryAll();
        // if (count($paymentHistories) > 0) {
        //     $secretKey = Yii::$app->params['stripe']['secretKey'];
        //     \Stripe\Stripe::setApiKey($secretKey);
        //     foreach ($paymentHistories as $paymentHistory) {
        //         $sessionId = $paymentHistory['session_id'];
        //         $session = \Stripe\Checkout\Session::retrieve($sessionId);
        //         $paymentIntentId = $session->payment_intent;
        //         $dateTime = strtotime('now');
        //         if ($dateTime - $paymentHistory['start_date'] >= 50 && !$paymentIntentId) {
        //             $sql = "UPDATE `image` SET available = 1, paid = false WHERE id IN " . $paymentHistory['ids'];
        //             $command = $connection->createCommand($sql);
        //             $result = $command->execute();
        //             $secondSql = "UPDATE payment_history SET end_date = " . $dateTime . " WHERE end_date IS NULL AND ids = '" . $paymentHistory['ids'] . "' AND session_id = '" . $sessionId . "'";
        //             $secondCommand = $connection->createCommand($secondSql);
        //             $secondResult = $secondCommand->execute();
        //         }
        //     }
        // } else {
        //     return [
        //         'success' => true,
        //     ];
        // }
    }
}