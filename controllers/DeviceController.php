<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends ActiveController
{
    public $modelClass = "app\models\Device";

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }
}
