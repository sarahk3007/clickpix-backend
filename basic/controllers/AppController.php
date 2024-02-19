<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use app\base\BaseAppController;
use Yii;

class AppController extends BaseAppController
{
    /**
     * @inheritdoc
     */
    protected $authExceptActions = ['generate-token'];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'generate-token' => ['GET'],
                    'get-visible-pixels' => ['GET'],
                    'get-random-ids' => ['POST'],
                    'send-user-sms' => ['POST'],
                    'verify-token' => ['POST'],
                    'create-payment-link' => ['POST'],
                    'block-pixels' => ['POST']
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'generate-token' => 'app\controllers\app\GenerateTokenAction',
            'get-visible-pixels' => 'app\controllers\app\GetVisiblePixelsAction',
            'get-random-ids' => 'app\controllers\app\GetRandomIdsAction',
            'send-user-sms' => 'app\controllers\app\SendUserSmsAction',
            'verify-token' => 'app\controllers\app\VerifyTokenAction',
            'create-payment-link' => 'app\controllers\app\CreatePaymentLinkAction',
            'block-pixels' => 'app\controllers\app\BlockPixelsAction',
        ];
    }
}
