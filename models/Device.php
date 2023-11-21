<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property string $id
 * @property string $view_id
 * @property string $name
 * @property int $width
 * @property int $height
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property int $deleted
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'view_id', 'name', 'width', 'height', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['width', 'height', 'deleted'], 'integer'],
            [['id', 'view_id'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 100],
            [['created_at', 'updated_at'], 'string', 'max' => 25],
            [['created_by', 'updated_by'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['view_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'view_id' => 'View ID',
            'name' => 'Name',
            'width' => 'Screen Width',
            'height' => 'Screen Height',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
        ];
    }

    public function beforeValidate()
    {
        if($this->isNewRecord)
        {
            if(!$this->id)
            {
                $this->id = Device::newID();
            }

            if(!$this->view_id)
            {
                $this->view_id = Device::newID();
            }

            if(!$this->created_at)
            {
                $this->created_at = date(DATE_ISO8601);
                $this->updated_at = $this->created_at;
            }

            $this->updated_by = $this->created_by;
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if($insert)
        {
            if(!$this->id)
            {
                $this->id = Device::newID();
            }

            if(!$this->view_id)
            {
                $this->view_id = Device::newID();
            }

            if(!$this->created_at)
            {
                $this->created_at = date(DATE_ISO8601);
                $this->updated_at = $this->created_at;
            }

            $this->updated_by = $this->created_by;
        }
        else
        {
            $this->updated_at = date(DATE_ISO8601);
        }

        return parent::beforeSave($insert);
    }

    public function getImages()
    {
        return $this->hasMany(Image::class, ['device_id' => 'id']);
    }

    public function asResultArray()
    {
        return [
            "id" => $this->view_id,
            "width" => $this->width,
            "height" => $this->height,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }

    public static function cleanID($id)
    {
        return trim(strtoupper($id));
    }

    public static function newID()
    {
        $timeMillis = floor(microtime(true) * 1000);

        $ar = unpack("C*", pack("J", $timeMillis));

        $hexStr = strtoupper(bin2hex(join(array_map("chr", $ar))));

        $hexStr .= strtoupper(bin2hex(random_bytes(8)));

        return sprintf("%s-%s-%s-%s-%s", substr($hexStr, 0, 8), substr($hexStr, 8, 4), substr($hexStr, 12, 4), substr($hexStr, 16, 4), substr($hexStr, 20, 12));
    }
}