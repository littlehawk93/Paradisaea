<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;

class ImageUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    private $filePath;

    public function rules()
    {
        return [
            [["file"], "required"],
            [["file"], "file", "skipOnEmpty" => false, "extensions" => "png, jpg", "maxFiles" => 1, "maxSize" => 1048576, "mimeTypes" => ["image/jpg", "image/jpeg", "image/png"]]
        ];
    }

    public function getFilePath() {
        return $this->filePath;
    }

    public function uploadAndResize($device_id, $width, $height)
    {
        $tempFile = $this->createTempFileName();

        if(!$tempFile || !$this->file->saveAs($tempFile))
        {
            return false;
        }

        $file = self::createFileName($device_id);

        if(!$file)
        {
            return false;
        }

        $result = self::resizeImage($tempFile, $file, $width, $height);
        $unlinkResult = unlink($tempFile);

        if(!$result || !$unlinkResult)
        {
            return false;
        }
        return $file;
    }

    private function createTempFileName() 
    {
        $dir = implode("/", [Yii::getAlias("@app"), "data", "tmp", ]);

        if(!file_exists($dir))
        {
            if(!mkdir($dir, 0775, true))
            {
                return false;
            }
        }

        return implode("/", [$dir, uniqid("img", true) . "." . $this->file->getExtension()]);
    }

    private function createFileName($device_id) 
    {
        $dir = implode("/", [Yii::getAlias("@app"), "data", "uploads", $device_id]);

        if(!file_exists($dir))
        {
            if(!mkdir($dir, 0775, true))
            {
                return false;
            }
        }

        $file = implode("/", [$dir, date("Ymd_His_") . uniqid() . "." . $this->file->getExtension()]);

        return $file;
    }

    private static function resizeImage($inputFile, $outputFile, $width, $height) 
    {
        $imagine = Image::getImagine();

        $img = $imagine->open($inputFile);

        Image::resize($img, $width, $height, false)->save($outputFile);
        return true;
    }
}