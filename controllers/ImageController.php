<?php

namespace app\controllers;

use Yii;
use app\models\Device;
use app\models\ImageType;
use app\models\Image;
use app\models\ImageUploadForm;
use yii\web\Response;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

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

        foreach($device->getImages()->orderBy(["created_at" => SORT_ASC])->all() as $image)
        {
            if(isset(Yii::$app->request->get()["idsOnly"])) {
                $results[] = $image->id;
            } else {
                $results[] = $image->asResultArray();
            }
        }

        return $this->asJson($results);
    }

    public function actionView($device_id, $image_id, $image_type)
    {
        if(!$image_type) 
        {
            $image_type = 1;
        }

        if(!is_numeric($image_type))
        {
            throw new BadRequestHttpException("Parameter 'image_type' must be numeric");
        }

        if(!is_numeric($image_id))
        {
            throw new BadRequestHttpException("Parameter 'image_id' must be numeric");
        }

        $image_type = intval($image_type);

        $type = ImageType::findOne($image_type);

        if(!$type)
        {
            throw new BadRequestHttpException("invalid imgtype provided");
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

        $responseData = $type->encodeImage($image->filepath, $device->width, $device->height);

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
            throw new NotFoundHttpException("Device not found");
        }

        $imageUploadForm = new ImageUploadForm();

        $imageUploadForm->file = UploadedFile::getInstanceByName("file"); 

        if(!$imageUploadForm->validate())
        {
            if($imageUploadForm->hasErrors())
            {
                $validationErrors = array();

                foreach($imageUploadForm->errors as $field => $field_errors)
                {
                    $validationErrors[] = $field . ": " . join(", ", $field_errors);
                }

                throw new BadRequestHttpException("Invalid request: " . join("& ", $validationErrors));
            }
        }

        $filePath = $imageUploadForm->uploadAndResize($device_id, $device->width, $device->height);

        if(!$filePath)
        {
            echo "TEST";
            throw new BadRequestHttpException("Invalid file uploaded");
        }

        $image = new Image();
        $image->device_id = $device->id;
        $image->created_by = Yii::$app->request->remoteIp;
        $image->filepath = $filePath;

        if(!$image->save(true))
        {
            throw new ServerErrorHttpException("Unable to save image");
        }

        if(isset(Yii::$app->request->get()["redirect"]))
        {
            return $this->redirect("/device/" . $device->view_id . "?success");
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
