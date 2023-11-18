<?php

namespace app\commands;

use app\models\Device;
use yii\console\Controller;

class DeviceController extends Controller
{
    public function actionCreate($screenwidth, $screenheight)
    {
        if(!is_numeric($screenwidth) || !is_numeric($screenheight)) 
        {
            $this->stderr("Screen width and height must be numeric");
            return 1;
        }

        $screenwidth = intval($screenwidth);
        $screenheight = intval($screenheight);

        if ($screenwidth <= 0 || $screenheight <= 0)
        {
            $this->stderr("Screen width and height must be greater than zero");
            return 2;
        }

        $device = new Device();
        $device->width = $screenwidth;
        $device->height = $screenheight;
        $device->deleted = 0;
        $device->created_by = get_current_user();

        if(!$device->save())
        {
            if($device->hasErrors())
            {
                $this->stderr("Invalid device data provided:\n");
                
                foreach($device->errors as $field => $field_errors)
                {
                    $this->stderr($field . " errors:\n");

                    foreach($field_errors as $field_error) {
                        $this->stderr("\t" . $field_error . "\n");
                    }
                }
            }
            else
            {
                $this->stderr("Failed to save device\n");
            }
            return 1;
        }
        
        $this->stdout("Device Created:\n");

        $this->stdout(str_pad("ID:", 12, " ") . $device->id . "\n");
        $this->stdout(str_pad("View ID:", 12, " ") . $device->view_id . "\n");
        $this->stdout(str_pad("Width:", 12, " ") . $device->width . "\n");
        $this->stdout(str_pad("Height:", 12, " ") . $device->height . "\n");
        $this->stdout(str_pad("Created By:", 12, " ") . $device->created_by . "\n");
        $this->stdout(str_pad("Created On:", 12, " ") . $device->created_at . "\n");
        
        return 0;
    }

    public function actionDisable($id)
    {
        $id = Device::cleanID($id);

        $device = Device::findOne($id);

        if(!$device)
        {
            $this->stderr("No device found with id: '" . $id . "'");
            return 1;
        }

        if($device->deleted)
        {
            $this->stderr("Device has already been disabled");
            return 1;
        }

        $device->updated_by = get_current_user();
        $device->updated_at = date(DATE_ISO8601);
        $device->deleted = 1;

        if(!$device->save())
        {
            if($device->hasErrors())
            {
                $this->stderr("Invalid device data provided:\n");
                
                foreach($device->errors as $field => $field_errors)
                {
                    $this->stderr($field . " errors:\n");

                    foreach($field_errors as $field_error) {
                        $this->stderr("\t" . $field_error . "\n");
                    }
                }
            }
            else
            {
                $this->stderr("Failed to disable device\n");
            }
            return 1;
        }

        $this->stdout("Device disabled!");
        return 0;
    }
}
