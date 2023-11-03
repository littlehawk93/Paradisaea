<?php

namespace app\controllers;

use app\models\Device;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends Controller
{
    public function beforeAction($action)
    {
        return true;
    }

    public function actionView($id)
    {
        $id = Device::cleanID($id);

        $device = Device::findOne(["view_id" => $id]);

        if(!$device || $device->deleted !== 0)
        {
            throw new NotFoundHttpException("device not found");
        }

        return $this->asJson([
            "id" => $device->view_id,
            "width" => $device->width,
            "height" => $device->height,
            "created_at" => $device->created_at,
            "updated_at" => $device->updated_at
        ]);
    }
}
