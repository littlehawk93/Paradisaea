<?php

namespace app\controllers;

use yii\rest\Controller;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    public function actionIndex($device_id)
    {
        return $this->asJson([
            "action" => "image/index",
            "device_id" => $device_id
        ]);
    }

    public function actionView($device_id, $image_id)
    {
        return $this->asJson([
            "action" => "image/view",
            "device_id" => $device_id,
            "image_id" => $image_id
        ]);
    }

    public function actionCreate($device_id)
    {
        return $this->asJson([
            "action" => "image/create",
            "device_id" => $device_id
        ]);
    }

    public function actionUpdate($device_id, $image_id)
    {
        return $this->asJson([
            "action" => "image/update",
            "device_id" => $device_id,
            "image_id" => $image_id
        ]);
    }

    public function actionDelete($device_id, $image_id)
    {
        return $this->asJson([
            "action" => "image/delete",
            "device_id" => $device_id,
            "image_id" => $image_id
        ]);
    }
}
