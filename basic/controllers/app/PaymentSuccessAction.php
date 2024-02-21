<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use app\controllers\app\BaseAction;

use Yii;

class PaymentSuccessAction extends BaseAction
{
    public function run()
    {
        // Retrieve the Checkout Session ID from the query parameters
        $checkoutSessionId = Yii::$app->request->get('session_id');

        if (!$checkoutSessionId) {
            throw new ForbiddenHttpException('You are not allowed to access this resource.');
        }

        // Verify the Checkout Session ID to ensure it's valid and retrieve the associated payment details
        $secretKey = Yii::$app->params['stripe']['secretKey'];
        \Stripe\Stripe::setApiKey($secretKey);

        try {
            $session = \Stripe\Checkout\Session::retrieve($checkoutSessionId);

            // Here you can access payment details and perform further actions based on your business logic
            $paymentIntentId = $session->payment_intent;
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
            
            $ids = Yii::$app->request->get('ids');
            $phone = Yii::$app->request->get('phone');
            $flag = Yii::$app->request->get('flag');
            $email = Yii::$app->request->get('email');
            $name = Yii::$app->request->get('name');
            $connection = Yii::$app->getDb();
            $dateTime = strtotime('now');
            if (!$ids || !$phone || !$flag || !$email || !$name) {
                throw new ForbiddenHttpException('You are not allowed to access this resource.');
            }
            $arrayIds = implode(",", $ids);
            $dateTime = strtotime('now');
            $paymentSql = "UPDATE payment_history SET end_date = '" . $dateTime . "' WHERE end_date IS NULL AND ids = '(" . $arrayIds . ")' AND session_id = '" . $checkoutSessionId . "'";
            $command = $connection->createCommand($paymentSql);
            $payment = $command->execute();
            if ($payment && $payment > 0) {
                $error = null;
            } else {
                $error = 'Payment history not updated';
            }
            $sql = "INSERT INTO image_user (image_id, phone, flag, email, name, created) VALUES ";
            foreach ($ids as $id) {
                $sql .= "(" . $id . ", '" . $phone . "', ". $flag . ", '". $email . "', '". $name . "', ". $dateTime .")";
                $sql .= ",";
            }
            $sql = substr_replace($sql,";",-1);
            $command = $connection->createCommand($sql);
            $insertResult = $command->execute();
            $idsArray = implode(",", $ids);
            if ($insertResult) {
                $sql = "UPDATE `image` SET paid = false, available = 1, flag = " . $flag . " WHERE id IN (" . $idsArray . ")";
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand($sql);
                $updateResult = $command->execute();
            }

            //TODO send email confirmation
            return $this->controller->render('/site/success', [
                'sessionId' => $checkoutSessionId,
            ]);
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            //return $this->redirect(['site/error']);
        }
    }
}