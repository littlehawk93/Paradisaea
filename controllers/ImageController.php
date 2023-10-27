<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }
}
