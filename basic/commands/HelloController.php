<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        ini_set('memory_limit','3G');
        $connection = Yii::$app->getDb();
        $sql = "INSERT INTO image_user (image_id, flag, email, name, created) VALUES ";
        for ($id = 0; $id <= 100000; $id = $id + 600) {
            if ($id == 0) {
                $newId = 1;
            }
            $j = $newId;
            for ($j = $newId + 1; $j <= $newId + 300; $j++) {
                $dateTime = rand(1, 1706443563);
                if ($j < 25000 || $j > 75000) {
                    $flag = 0;
                } else {
                    $flag = 1;
                }
                $sql .= "(" . $j . ", 0547488988, ". $flag . ", 'rebeceva@gmail.com', 'rebecca test', ". $dateTime .")";
                $sql .= ",";
            }
        }

        $sql = substr_replace($sql,";",-1);
        $command = $connection->createCommand($sql);
        $result = $command->execute();
    }
}
