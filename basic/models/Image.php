<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int|null $flag
 * @property int|null $available
 * @property int|null $paid
 *
 * @property ImageUser[] $imageUsers
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flag', 'available', 'paid'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'flag' => 'Flag',
            'available' => 'Available',
            'paid' => 'Paid',
        ];
    }

    /**
     * Gets query for [[ImageUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImageUsers()
    {
        return $this->hasMany(ImageUser::class, ['image_id' => 'id']);
    }
}
