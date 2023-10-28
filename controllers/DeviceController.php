<?php

namespace app\controllers;

use yii\rest\Controller;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends Controller
{
    public function actionView($id)
    {
        return $this->asJson([
            "action" => "device/view",
            "device_id" => $id
        ]);
    }

    public function actionCreate()
    {
        return $this->asJson([
            "action" => "device/create"
        ]);
    }

    public function actionDelete($id)
    {
        return $this->asJson([
            "action" => "device/delete",
            "device_id" => $id
        ]);
    }
}
