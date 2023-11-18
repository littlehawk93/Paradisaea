<?php

/** @var yii\web\View $this */
/** @var bool $success */

$this->title = "Paradisaea | Upload Image";
?>
<h4>Upload an Image</h4>
<form class="mt-4" action="<?= '/api/device/' . $device->view_id . '/image?redirect' ?>" method="POST" enctype="multipart/form-data">
    <label>Select an image file to upload: </label>
    <input type="file" name="file" id="id" accept=".jpg,.png,image/jpg,image/jpeg,image/png" /><br />
    <button type="submit" class="btn btn-outline-dark mt-2">Upload Image</button>
</form>
<?php if ($success): ?>
<div class="mt-4 alert alert-success" role="alert">
    Image successfully uploaded!
</div>
<?php endif ?>