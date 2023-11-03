<?php

namespace app\controllers;

use app\models\Device;
use app\models\Image;
use yii\web\Response;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    public function beforeAction($action)
    {
        return true;
    }

    public function actionIndex($device_id)
    {
        return $this->asJson([
            "action" => "image/index",
            "device_id" => $device_id
        ]);
    }

    public function actionView($device_id, $image_id)
    {
        if(!is_numeric($image_id))
        {
            throw new BadRequestHttpException("image_id must be numeric");
        }

        $image_id = intval($image_id);

        $device_id = Device::cleanID($device_id);

        $device = Device::findOne($device_id);

        if(!$device)
        {
            $device = Device::findOne(["view_id" => $device_id]);
        }

        if(!$device || $device->deleted !== 0)
        {
            throw new NotFoundHttpException("device not found");
        }

        $image = Image::findOne(["id" => $image_id, "device_id" => $device->id]);

        if(!$image || !file_exists($image->filepath))
        {
            throw new NotFoundHttpException("image not found");
        }

        $responseData = file_get_contents($image->filepath);

        if(!$responseData)
        {
            throw new ServerErrorHttpException("unable to read image file data");
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add("Content-Type", "text/plain");
        return $responseData;
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
