<?php

namespace app\models;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "image_type".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property Image[] $images
 */
class ImageType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'bpp'], 'required'],
            [['bpp'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'bpp' => 'Bits Per Pixel',
            'description' => 'Description',
        ];
    }

    public function encodeImage($file, $width, $height) {

        $result = "";

        $imagine = Image::getImagine();
        $img = $imagine->open($file);

        for($y=0;$y<$height;$y++)
        {
            $row = array();

            for($x=0;$x<$width;$x++)
            {
                $col = $img->getColorAt(new \Imagine\Image\Point($x, $y));

                $intCol = 0;

                $r = $col->getValue(\Imagine\Image\Palette\Color\ColorInterface::COLOR_RED);
                $g = $col->getValue(\Imagine\Image\Palette\Color\ColorInterface::COLOR_GREEN);
                $b = $col->getValue(\Imagine\Image\Palette\Color\ColorInterface::COLOR_BLUE);

                $intCol |= ($r & 0xFF) << 16;
                $intCol |= ($g & 0xFF) << 8;
                $intCol |= $b & 0xFF;

                array_push($row, $intCol);
            }

            $result .= self::encodeRow($row, $this) . "\n";
        }
        return $result;
    }

    private static function encodeRow($row, $imageType)
    {
        if($imageType->id === 1)
        {
            return self::encode1BitBW($row);
        }

        $result = "";

        foreach($row as $col)
        {
            $r = ($col >> 16) & 0xFF;
            $g = ($col >> 8) & 0xFF;
            $b = $col & 0xFF;

            switch($imageType->id)
            {
                case 2: // Monochrome 8 Bit
                    $result .= self::encode8BitGreyscale($r, $g, $b);
                    break;
                case 3: // 4 Bit RGB
                    $result .= self::encode12BitRGB($r, $g, $b);
                    break;
                case 4: // Full RGB
                    $result .= self::encode24BitRGB($r, $g, $b);
                    break;
                default:
                    return false;
            }
        }
        return $result;
    }

    private static function encode1BitBW($row)
    {
        $result = "";
        $curr = 0;
        $idx = 0;

        foreach($row as $col)
        {
            $r = ($col >> 16) & 0xFF;
            $g = ($col >> 8) & 0xFF;
            $b = $col & 0xFF;
            $grey = self::computeLuminance($r, $g, $b);
            $val = $grey >= 128 ? 1 : 0;

            $curr |= $val << (3 - $idx);
            $idx++;

            if($idx >= 4)
            {
                $result .= strtoupper(dechex($curr));
                $curr = 0;
                $idx = 0;
            }
        }

        return $result;
    }

    private static function encode8BitGreyscale($r, $g, $b)
    {
        return self::encodeByte(self::computeLuminance($r, $g, $b));
    }

    private static function encode12BitRGB($r, $g, $b)
    {
        $r = intval($r / 255.0 * 15);
        $g = intval($g / 255.0 * 15);
        $b = intval($b / 255.0 * 15);
        return strtoupper(dechex($r) . dechex($g) . dechex($b));
    }

    private static function encode24BitRGB($r, $g, $b)
    {
        return self::encodeByte($r) . self::encodeByte($g) . self::encodeByte($b);
    }

    private static function encodeByte($b)
    {
        return strtoupper(dechex(($b >> 4) & 0x0F) . dechex($b & 0x0F));
    }

    private static function computeLuminance($r, $g, $b)
    {
        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;

        $val = intval(($r * 0.299 + $g * 0.587 + $b * 0.114) * 255);

        if($val < 0)
        {
            throw new \Exception("unexpected value < 0");
        }
        else if ($val > 255)
        {
            throw new \Exception("unexpected value > 255");
        }
        return $val;
    }
}
