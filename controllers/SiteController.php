<?php

namespace app\controllers;

use Yii;
use app\models\Device;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class SiteController extends Controller
{
    public $layout = "default";

    public function actionIndex()
    {
        return $this->render("index");
    }

    public function actionDevice($device_id)
    {
        $device_id = Device::cleanID($device_id);

        $device = Device::findOne($device_id);

        if(!$device)
        {
            $device = Device::findOne(["view_id" => $device_id]);
        }

        if(!$device || $device->deleted !== 0)
        {
            throw new NotFoundHttpException("Device not found");
        }

        return $this->render("device", [
            "device" => $device,
            "success" => isset(Yii::$app->request->get()["success"])
        ]);
    }

    public function actionError()
    {
        return $this->render("error", [
            "exception" => Yii::$app->errorHandler->exception
        ]);
    }
}