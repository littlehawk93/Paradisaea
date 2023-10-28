<?php

namespace app\controllers;
use yii\web\Controller;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class SiteController extends Controller
{
    public $layout = false;

    public function actionIndex()
    {
        return $this->render("index");
    }
}