<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image_user".
 *
 * @property int $id
 * @property int|null $image_id
 * @property int|null $phone
 * @property int|null $flag
 * @property string|null $email
 * @property string|null $name
 * @property string|null $created
 *
 * @property Image $image
 */
class ImageUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_id', 'phone', 'flag', 'created'], 'integer'],
            [['email'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 500],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['image_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_id' => 'Image ID',
            'phone' => 'Phone',
            'flag' => 'Flag',
            'email' => 'Email',
            'name' => 'Name',
            'created' => 'Created',
        ];
    }

    /**
     * Gets query for [[Image]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['id' => 'image_id']);
    }
}
