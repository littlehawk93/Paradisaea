<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $device_id
 * @property string $filepath
 * @property string $created_at
 * @property string $created_by
 *
 * @property Device $device
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
            [['device_id', 'filepath', 'created_at', 'created_by'], 'required'],
            [['device_id'], 'string', 'max' => 36],
            [['filepath'], 'string', 'max' => 2000],
            [['created_at'], 'string', 'max' => 25],
            [['created_by'], 'string', 'max' => 50],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::class, 'targetAttribute' => ['device_id' => 'id']],
            [['image_data'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'filepath' => 'Filepath',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function beforeValidate()
    {
        if($this->isNewRecord)
        {
            if(!$this->created_at)
            {
                $this->created_at = date(DATE_ISO8601);
            }
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            if(!$this->created_at)
            {
                $this->created_at = date(DATE_ISO8601);
            }
        }

        return parent::beforeSave($insert);
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

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) 
        {
            return false;
        }

        return !unlink($this->filepath);
    }

    public function asResultArray()
    {
        return [
            "id" => $this->id,
            "created_at" => $this->created_at
        ];
    }
}
