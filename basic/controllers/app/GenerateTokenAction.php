<?php

namespace app\contollers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\BaseAction;

use Yii;

class GenerateTokenAction extends BaseAction
{
    public function run()
    {
        $bearerToken = AccessToken::create(24 * 30 * 12);

        return [
            'access_token' => $token
        ];
    }
}