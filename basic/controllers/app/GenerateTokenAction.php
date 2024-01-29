<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;
use app\models\AccessToken;

use Yii;

class GenerateTokenAction extends BaseAction
{
    public function run()
    {
        $bearerToken = AccessToken::create(24 * 30 * 12);

        return [
            'access_token' => $bearerToken
        ];
    }
}