<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property string $id
 * @property string $view_id
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
            [['id', 'view_id', 'width', 'height', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['width', 'height', 'deleted'], 'integer'],
            [['id', 'view_id'], 'string', 'max' => 36],
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
            'width' => 'Screen Width',
            'height' => 'Screen Height',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
        ];
    }

    public static function cleanID($id)
    {
        return trim(strtoupper($id));
    }

    public static function newID()
    {
        $time = floor(microtime(true) * 1000);
        
        $bytes = random_bytes(8);

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', $time / 65536, $time % 65536, mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}
