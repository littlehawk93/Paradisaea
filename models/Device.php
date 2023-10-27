<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device".
 *
 * @property string $id
 * @property string $view_id
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
            [['id', 'view_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'required'],
            [['deleted'], 'integer'],
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
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
        ];
    }
}
