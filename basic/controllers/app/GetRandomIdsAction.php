<?php

namespace app\controllers\app;

use yii\base\Action;
use yii\helpers\ArrayHelper;
use app\controllers\app\BaseAction;

use Yii;

class GetRandomIdsAction extends BaseAction
{
    public function run()
    {
        ini_set('memory_limit','2G');
        $postData = $this->controller->requestData;
        if (!isset($postData['num'])) {
            Yii::$app->response->statusCode = 400;
            return [
                'error_message' => 'You have to enter a number of pixels'
            ];
        }
        
        $sql = "SELECT id FROM image WHERE available = 1";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $ids = $command->queryAll();

        // if ($postData['num'] / 10 > count($ids)) {
        //     Yii::$app->response->statusCode = 400;
        //     return [
        //         'error_message' => 'There is not enough pixels left to buy'
        //     ]; 
        // }
        // $flag = $postData['flag'];
        // $secondSql = "SELECT DISTINCT(image.id) FROM image LEFT JOIN image_user ON image.id = image_user.image_id WHERE image_user.image_id IS NOT NULL AND image.flag = " . $flag;
        // $connection = Yii::$app->getDb();
        // $secondCommand = $connection->createCommand($secondSql);
        // $flagIds = $secondCommand->queryAll();

        // $consecutiveIds = $this->findAdjacentSquare(array_column($ids, 'id'), array_column($flagIds, 'id'), $postData['num'] / 10);
        // if (empty($consecutiveIds)) {
        //     $consecutiveIds = array_rand(array_column($ids, 'id'), $postData['num'] / 10);
        // }

        $consecutiveIds = array_rand(array_column($ids, 'id'), $postData['num']);
        if ($postData['num'] == 1) {
            $consecutiveIds = [$consecutiveIds];
        }

        return [
            'data' => $consecutiveIds
        ];

    }

    // function isAdjacentToFlag($grid, $flagGrid, $row, $col, $sideLength) {
    //     for ($i = 0; $i < $sideLength; $i++) {
    //         for ($j = 0; $j < $sideLength; $j++) {
    //             // Check all surrounding cells for flag
    //             for ($dr = -1; $dr <= $sideLength; $dr++) {
    //                 for ($dc = -1; $dc <= $sideLength; $dc++) {
    //                     if (
    //                         $row + $i + $dr >= 0 && $row + $i + $dr < 1000 &&
    //                         $col + $j + $dc >= 0 && $col + $j + $dc < 100 &&
    //                         ($dr == -1 || $dr == $sideLength || $dc == -1 || $dc == $sideLength)
    //                     ) {
    //                         if ($flagGrid[$row + $i + $dr][$col + $j + $dc]) {
    //                             return true;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return false;
    // }

    // public function findAdjacentSquare($ids, $flagIds, $num) {
    //     // Convert the $ids array into a 2D grid representation
    //     $grid = array_fill(0, 1000, array_fill(0, 100, false));
    //     foreach ($ids as $id) {
    //         $row = intval(($id - 1) / 100);
    //         $col = ($id - 1) % 100;
    //         $grid[$row][$col] = true;
    //     }
    
    //     // Convert $flagIds to a 2D grid of flags
    //     $flagGrid = array_fill(0, 1000, array_fill(0, 100, false));
    //     foreach ($flagIds as $flagId) {
    //         $row = intval(($flagId - 1) / 100);
    //         $col = ($flagId - 1) % 100;
    //         $flagGrid[$row][$col] = true;
    //     }
    
    //     // Determine the size of the square needed
    //     $sideLength = intval(sqrt($num));
    //     if ($sideLength * $sideLength != $num) {
    //         // Si ce n'est pas un carré parfait, trouver les dimensions du rectangle le plus proche
    //         $bestDiff = 1000000;
    //         $bestDims = [1, $num];
            
    //         // Parcourir tous les diviseurs possibles de la surface
    //         for ($length = 1; $length <= sqrt($num); $length++) {
    //             if ($num % $length == 0) {
    //                 $width = $num / $length;
    //                 $diff = abs($length - $width);
    //                 if ($diff < $bestDiff) {
    //                     $bestDiff = $diff;
    //                     $bestDims = [$length, $width];
    //                 }
    //             }
    //         }
    //         $sideLength = $bestDims[0];
    //         $sideWidth = $bestDims[1];
    //     } else {
    //         $sideLength = $sideLength;
    //         $sideWidth = $sideLength;
    //     }

    //     // Find a square of size $sideLength x $sideLength adjacent to a flag
    //     for ($row = 0; $row <= 1000 - $sideLength; $row++) {
    //         for ($col = 0; $col <= 100 - $sideWidth; $col++) {
    //             $allAvailable = true;
    //             for ($i = 0; $i < $sideLength; $i++) {
    //                 for ($j = 0; $j < $sideWidth; $j++) {
    //                     if (!$grid[$row + $i][$col + $j]) {
    //                         $allAvailable = false;
    //                         break 2;
    //                     }
    //                 }
    //             }
    //             if ($allAvailable && $this->isAdjacentToFlag($grid, $flagGrid, $row, $col, $sideLength)) {
    //                 // Return the IDs of the found square
    //                 $result = [];
    //                 for ($i = 0; $i < $sideLength; $i++) {
    //                     for ($j = 0; $j < $sideWidth; $j++) {
    //                         $result[] = ($row + $i) * 100 + $col + $j + 1;
    //                     }
    //                 }
    //                 return $result;
    //             }
    //         }
    //     }

    //     return []; // Return empty if no suitable square is found
    // }
}