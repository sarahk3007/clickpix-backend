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
    }
}