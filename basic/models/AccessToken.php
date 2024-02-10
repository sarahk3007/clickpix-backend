<?php

namespace app\models;

use Yii;

use app\models\users\Contacts;

/**
 * This is the model class for table "access_token".
 *
 * @property int $id
 * @property string $type
 * @property string $issued_date
 * @property string $issue_ip
 * @property string $valid_until
 * @property int $used
 * @property string $used_date
 * @property string $used_ip
 * @property string $token
 * @property int $total_time
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'issued_date', 'issue_ip', 'valid_until', 'token'], 'required'],
            [['total_time'], 'integer'],
            [['issued_date', 'valid_until', 'used_date'], 'safe'],
            [['type'], 'string', 'max' => 60],
            [['issue_ip', 'used_ip'], 'ip'],
            [['token'], 'string', 'max' => 400],
            [['used'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('access_token', 'ID'),
            'type' => Yii::t('access_token', 'Type'),
            'issued_date' => Yii::t('access_token', 'Issued Date'),
            'issue_ip' => Yii::t('access_token', 'Issue Ip'),
            'valid_until' => Yii::t('access_token', 'Valid Until'),
            'used' => Yii::t('access_token', 'Used'),
            'used_date' => Yii::t('access_token', 'Used Date'),
            'used_ip' => Yii::t('access_token', 'Used Ip'),
            'token' => Yii::t('access_token', 'Token'),
            'total_time' => Yii::t('access_token', 'Total Time'),
        ];
    }

    /**
     * Generate a new temporary access token
     *
     * @param int $userId
     * @param string $type
     * @param int $validHours
     * @return string|FALSE
     */
    public static function create(int $validHours = 72, $type = 'bearer_token')
    {
        if ($type == 'bearer_token')
            $token = Yii::$app->getSecurity()->generateRandomString();
        else {
            $token = strval(rand(100000, 999999));
        }
        $model = new static();

        $model->attributes = [
            'type' => $type,
            'issued_date' => (new \DateTime())->format('Y-m-d H:i:s'),
            'valid_until' => (new \DateTime())->setTimestamp(strtotime("+{$validHours} hours"))->format('Y-m-d H:i:s'),
            'token' => $token,
            'used' => $type == 'bearer_token' ? 1 : 0,
            'issue_ip' => Yii::$app->request->userIP ?? getHostByName(getHostName()),
        ];

        if (!$model->save() && YII_DEBUG) {
            echo "<pre>[".print_r($model->errors, true)."]</pre>";
            exit;
        } elseif ($model->save()) {
            return $token;
        } else {
            return FALSE;
        }
    }

    /**
     * Mark an access token as used
     *
     * @param int $userId
     * @param string $token
     * @return bool
     */
    public static function markAsUsed(string $token): bool
    {
        if ($model = static::findOne(['token' => $token, 'type' => 'sms_token'])) {
            $model->used = 1;
            $model->used_date = (new \DateTime())->format('Y-m-d H:i:s');
            $model->used_ip = Yii::$app->request->userIP;
            return $model->save();
        } else {
            return false;
        }
    }

    /**
     * Validate an access token
     *
     * @param string $token
     * @param string $type
     * @return bool
     */
    public static function validateToken(string $token, string $type): bool
    {
        $success = false;
        
        if ($model = static::findOne(['token' => $token, 'type' => $type , 'used' => 0])) {
            if(time() > strtotime($model->valid_until)) {
                Yii::error("Access token {$token} expired on {$model->valid_until}!");
            } else {
                $success = true;
            }
        } else {
            Yii::error("Access token {$token} of type {$type} not found for user");
        }

        return $success;
    }
}
