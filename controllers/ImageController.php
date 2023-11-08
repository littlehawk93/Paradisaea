<?php

namespace app\controllers;

use app\models\Device;
use app\models\Image;
use app\models\ImageType;
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
    public function actionIndex($device_id)
    {
        $device_id = Device::cleanID($device_id);

        $device = Device::findOne($device_id);

        if(!$device || $device->deleted !== 0)
        {
            throw new NotFoundHttpException("device not found");
        }

        $results = array();

        foreach($device->getImages()->orderBy(["created_on" => SORT_ASC]) as $image)
        {
            $results[] = $image->asResultArray();
        }

        return $this->asJson($results);
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

        $fileData = Yii::$app->request->post("fileData");
        $imageTypeId = Yii::$app->request->post("imageType");

        $imageType = ImageType::findOne($imageTypeId);

        if(!$imageType)
        {
            throw new BadRequestHttpException("invalid image type");
        }

        $image = new Image();
        $image->type_id = $imageType->id;
        $image->type = $imageType;
        $image->image_data = $fileData;
        $image->device = $device;
        $image->device_id = $device->id;
        $image->created_by = Yii::$app->request->remoteIp;

        if(!$image->validate())
        {
            throw new BadRequestHttpException("invalid image data");
        }

        if(!$image->save(false))
        {
            throw new ServerErrorHttpException("unable to save image");
        }

        return $this->asJson($image->asResultArray());
    }

    public function actionDelete($device_id, $image_id)
    {
        $image = Image::findOne(["id"=> $image_id, "device_id" => $device_id]);

        if(!$image)
        {
            throw new NotFoundHttpException("image not found");
        }

        if(!$image->delete())
        {
            throw new ServerErrorHttpException("failed to delete image");
        }

        Yii::$app->response->statusCode = 204; // no content
        return "";
    }
}
