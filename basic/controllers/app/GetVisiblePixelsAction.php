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
        $sql = "SELECT image.id, image.available, image.flag FROM image LEFT JOIN image_user ON image.id = image_user.image_id WHERE image_user.image_id IS NOT NULL";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        
        $ids = array_column($result, 'id');

        $notVisible = "SELECT id FROM image WHERE available = 0 AND id NOT IN (" . implode(',', $ids) . ")";
        $noVisibleCommand = $connection->createCommand($notVisible);
        $notVisibleArray = $noVisibleCommand->queryAll();

        return [
            'visiblePixels' => $result,
            'notVisibleNotAvailable' => $notVisibleArray,
        ];
    }
}