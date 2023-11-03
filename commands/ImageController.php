<?php

namespace app\commands;

use app\models\Image;
use yii\console\Controller;

class ImageController extends Controller
{
    public function actionDelete($id)
    {
        $image = Image::findOne($id);

        if(!$image)
        {
            $this->stderr("No image found with id: '" . $id . "'");
            return 1;
        }

        if(!unlink($image->filepath))
        {
            $this->stderr("Failed to delete image data saved at '" . $image->filepath . "'");
            return 1;
        }

        if(!$image->delete())
        {
            $this->stderr("Failed to delete image record in database");
            return 1;
        }

        $this->stdout("Image deleted!");
        return 0;
    }
}
