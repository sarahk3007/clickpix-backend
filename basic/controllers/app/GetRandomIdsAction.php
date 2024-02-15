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

        $consecutiveIds = $this->getConsecutiveIdsFromArray(array_column($ids, 'id'), $postData['num']);

        return [
            'data' => $consecutiveIds
        ];

    }

    public function getConsecutiveIdsFromArray(array $ids, $x) 
    {
        sort($ids); // Sort the array to ensure consecutive sequences are grouped together

        $consecutiveSequences = [];
        $currentSequence = [];
    
        foreach ($ids as $id) {
            if (empty($currentSequence) || $id == end($currentSequence) + 1) {
                $currentSequence[] = $id;
            } else {
                $consecutiveSequences[] = $currentSequence;
                $currentSequence = [$id];
            }
        }
        $consecutiveSequences[] = $currentSequence; // Add the last sequence
    
        // Filter sequences with length less than X
        $consecutiveSequences = array_filter($consecutiveSequences, function ($sequence) use ($x) {
            return count($sequence) >= $x;
        });
    
        if (empty($consecutiveSequences)) {
            return []; // No consecutive sequences of length >= X found
        }
    
        // Choose a random consecutive sequence
        $randomSequenceIndex = array_rand($consecutiveSequences);
        $randomSequence = $consecutiveSequences[$randomSequenceIndex];
    
        // Choose a random starting index within the chosen sequence
        $maxStartIndex = count($randomSequence) - $x;
        $startIndex = mt_rand(0, $maxStartIndex);
    
        // Select X consecutive IDs from the chosen sequence
        return array_slice($randomSequence, $startIndex, $x);
    }
    
}