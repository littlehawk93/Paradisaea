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
    public $image_data;

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
            'type_id' => 'Type ID',
            'device_id' => 'Device ID',
            'filepath' => 'Filepath',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert && !$this->image_data)
        {
            return false;
        }
        else if($insert)
        {
            $this->created_at = date(DATE_ISO8601);
        }

        if ($this->image_data)
        {
            return file_put_contents($this->filepath, $this->image_data, 0775);
        }

        return parent::beforeSave($insert);
    }

    public function getWidth()
    {
        if($this->image_data)
        {
            $lines = $this->getImageLines();

            $max = 0;

            foreach($lines as $line)
            {
                if(strlen($line) > $max)
                {
                    $max = strlen($line);
                }
            }

            return $max * 4 / $this->type->getBitsPerPixel();
        }
        return 0;
    }

    public function getHeight()
    {
        if($this->image_data)
        {
            $lines = $this->getImageLines();

            return count($lines);
        }
        return 0;
    }

    public function getImageMetaData()
    {
        $headerLine = trim(substr($this->image_data, 0, strpos($this->image_data, "\n", 0)));
        return explode("|", $headerLine);
    }

    public function getImageLines()
    {
        $lines = explode("\n", $this->image_data);

        for($i = 0; $i < count($lines); $i++)
        {
            $lines[$i] = trim($lines[$i]);
        }

        array_splice($lines, 0, 1);
        
        return $lines;
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
            "type" => $this->getType(),
            "created_at" => $this->created_at,
            "width" => $this->getWidth(),
            "height" => $this->getHeight()
        ];
    }
}
