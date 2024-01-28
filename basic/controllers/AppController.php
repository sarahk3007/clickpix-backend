<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use app\base\BaseAppController;

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
                    'get-visible-pixels' => ['GET']
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
            'get-visible-pixels' => 'app\controllers\app\GetVisiblePixelsAction'
        ];
    }
}
