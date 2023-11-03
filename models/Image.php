<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $type_id
 * @property string $device_id
 * @property string $filepath
 * @property string $created_at
 * @property string $created_by
 *
 * @property Device $device
 * @property ImageType $type
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
            [['type_id', 'device_id', 'filepath', 'created_at', 'created_by'], 'required'],
            [['type_id'], 'integer'],
            [['device_id'], 'string', 'max' => 36],
            [['filepath'], 'string', 'max' => 2000],
            [['created_at'], 'string', 'max' => 25],
            [['created_by'], 'string', 'max' => 50],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::class, 'targetAttribute' => ['device_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImageType::class, 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'device_id' => 'Device ID',
            'filepath' => 'Filepath',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[Device]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::class, ['id' => 'device_id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ImageType::class, ['id' => 'type_id']);
    }

    /**
     * 
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) 
        {
            return false;
        }

        return !unlink($this->filepath);
    }
}
