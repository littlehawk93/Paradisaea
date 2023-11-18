<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\web\HttpException $exception */

$this->title = "Paradisaea | Error";
?>

<h2 class="text-danger"><?= $exception->statusCode ?> <?= Html::encode($exception->getMessage()) ?></h2>
<p>Unfortunately an error has occurred.</p>