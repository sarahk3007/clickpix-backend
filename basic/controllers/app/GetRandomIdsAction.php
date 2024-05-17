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

        $consecutiveIds = $this->getRectangleIdsFromArray(array_column($ids, 'id'), $postData['num'] / 10);

        return [
            'data' => $consecutiveIds
        ];

    }

    // Helper function to check if a given rectangle is adjacent to any unavailable cell
    public function isAdjacentToUnavailable($matrix, $rows, $cols, $i, $j, $height, $width) {
        for ($r = $i - 1; $r <= $i + $height; $r++) {
            for ($c = $j - 1; $c <= $j + $width; $c++) {
                if ($r >= 0 && $r < $rows && $c >= 0 && $c < $cols) {
                    if (($r < $i || $r >= $i + $height || $c < $j || $c >= $j + $width) &&
                        !$matrix[$r][$c]) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getRectangleIdsFromArray(array $ids, $x) 
    {
        $rows = 1000;
        $cols = 100;

        // Step 1: Convert IDs to a set for fast lookup
        $availableSet = array_flip($ids);
        
        // Step 2: Create the availability matrix
        $availabilityMatrix = array_fill(0, $rows, array_fill(0, $cols, false));
        
        foreach ($ids as $id) {
            $row = intdiv($id - 1, $cols);
            $col = ($id - 1) % $cols;
            $availabilityMatrix[$row][$col] = true;
        }

        // Step 3: Find rectangles
        for ($height = 1; $height <= $rows; $height++) {
            for ($width = 1; $width <= $cols; $width++) {
                if ($height * $width < $x) {
                    continue; // Skip rectangles that are too small
                }

                for ($i = 0; $i <= $rows - $height; $i++) {
                    for ($j = 0; $j <= $cols - $width; $j++) {
                        // Check if this rectangle is fully available
                        $isAvailable = true;
                        for ($r = $i; $r < $i + $height; $r++) {
                            for ($c = $j; $c < $j + $width; $c++) {
                                if (!$availabilityMatrix[$r][$c]) {
                                    $isAvailable = false;
                                    break 2;
                                }
                            }
                        }

                        if ($isAvailable && $this->isAdjacentToUnavailable($availabilityMatrix, $rows, $cols, $i, $j, $height, $width)) {
                            $result = [];
                            for ($r = $i; $r < $i + $height; $r++) {
                                for ($c = $j; $c < $j + $width; $c++) {
                                    $result[] = $r * $cols + $c + 1;
                                }
                            }

                            if (count($result) >= $x) {
                                return array_slice($result, 0, $x);
                            }
                        }
                    }
                }
            }
        }
        
        return []; // No suitable rectangle found
    }

    // public function getConsecutiveIdsFromArray(array $ids, $x) 
    // {
    //     $availableCells = [];
    //     $sql = "SELECT id FROM image WHERE available = 1";
    //     $connection = Yii::$app->getDb();
    //     $result = $connection->query($sql);
    //     if ($result->num_rows > 0) {
    //         while($row = $result->fetch_assoc()) {
    //             $availableCells[] = $row["id"];
    //         }
    //     } else {
    //         return [];
    //     }

    //     $gridSize = sqrt(count($availableCells));
    //     $gridSize = floor($gridSize);

    //     sort($ids); // Sort the array to ensure consecutive sequences are grouped together

    //     $consecutiveSequences = [];
    //     $currentSequence = [];
    
    //     foreach ($ids as $id) {
    //         if (empty($currentSequence) || $id == end($currentSequence) + 1) {
    //             $currentSequence[] = $id;
    //         } else {
    //             $consecutiveSequences[] = $currentSequence;
    //             $currentSequence = [$id];
    //         }
    //     }
    //     $consecutiveSequences[] = $currentSequence; // Add the last sequence
    
    //     // Filter sequences with length less than X
    //     $consecutiveSequences = array_filter($consecutiveSequences, function ($sequence) use ($x) {
    //         return count($sequence) >= $x;
    //     });
    
    //     if (empty($consecutiveSequences)) {
    //         return []; // No consecutive sequences of length >= X found
    //     }
    
    //     // Choose a random consecutive sequence
    //     $randomSequenceIndex = array_rand($consecutiveSequences);
    //     $randomSequence = $consecutiveSequences[$randomSequenceIndex];
    
    //     // Choose a random starting index within the chosen sequence
    //     $maxStartIndex = count($randomSequence) - $x;
    //     $startIndex = mt_rand(0, $maxStartIndex);
    
    //     // Select X consecutive IDs from the chosen sequence
    //     return array_slice($randomSequence, $startIndex, $x);
    // }
    
}