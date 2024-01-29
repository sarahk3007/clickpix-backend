<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use app\base\BaseAppController;
use app\models\AccessToken;
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
            'generate-token' => Yii::getAlias('@app') . '\controllers\app\GenerateTokenAction',
            'get-visible-pixels' => Yii::getAlias('@app') . '\controllers\app\GetVisiblePixelsAction',
        ];
    }

    // public function actionGenerateToken() 
    // {
    //     $bearerToken = AccessToken::create(24 * 30 * 12);

    //     return [
    //         'access_token' => $bearerToken
    //     ];
    // }

    // public function actionGetVisiblePixels()
    // {
    //     $sql = "SELECT * FROM image LEFT JOIN image_user ON image.id = image_user.image_id WHERE image_user.image_id IS NOT NULL";
    //     $connection = Yii::$app->getDb();
    //     $command = $connection->createCommand($sql);
    //     $result = $command->execute();

    //     return [
    //         'visiblePixels' => $result,
    //     ];
    // }
}
