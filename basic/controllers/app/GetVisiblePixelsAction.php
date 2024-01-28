<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;

use Yii;

class GetVisiblePixelsAction extends Action
{
    public function run()
    {
        $sql = "SELECT * FROM image LEFT JOIN image_user ON image.id = image_user.image_id WHERE image_user.image_id IS NOT NULL";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->execute();

        return [
            'visiblePixels' => $result,
        ];
    }
}