<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;

use Yii;

class GetVisiblePixelsAction extends BaseAction
{
    public function run()
    {
        $sql = "SELECT image.id FROM image LEFT JOIN image_user ON image.id = image_user.image_id WHERE image_user.image_id IS NOT NULL";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        return [
            'visiblePixels' => $result,
        ];
    }
}