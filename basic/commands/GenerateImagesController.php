<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Image;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GenerateImagesController extends Controller
{
    /**
     * This command generate all images into the table
     * @return int Exit code
     */
    public function actionIndex()
    {
        ini_set('memory_limit','2G');
        $sql = "INSERT INTO image_user (image_id, phone, flag, email, name, created) VALUES ";
        for ($id = 0; $id <= 1000000; $id = $id + 15) {
            if ($id == 0) {
                $newId = 1;
            } else {
                $newId = $id;
            }
            $dateTime = rand(1, 1706443563);
            if ($newId < 250000 || $newId > 750000) {
                $flag = 0;
            } else {
                $flag = 1;
            }
            $sql .= "(" . $newId . ", 0547488988, ". $flag . ", 'rebeceva@gmail.com', 'rebecca test', ". $dateTime .")";
            if ($id == 999990) {
                $sql .= ";";
            } else {
                $sql .= ",";
            }
        }
        // for ($id = 1; $id <= 1000000; $id++) {
        //     $sql .= "(" . $id . ")";
        //     if ($id == 1000000) {
        //         $sql .= ";";
        //     } else {
        //         $sql .= ",";
        //     }
        // }
        // for ($i=500001; $i < 1000000 ; $i++) {
        //     $sql .= "(" . $i;
        //     if ($i < 750000) {
        //         $flag = 1;
        //     } else {
        //         $flag = 0;
        //     }
        //     $sql .= "," . $flag . ",1,0)";
        //     if ($i == 999999) {
        //         $sql .= ";";
        //     } else {
        //         $sql .= ",";
        //     }
        // }
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->execute();
        Yii::info(print_r($result, true), 'sql');
    }
}