<?php 

use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">

        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <header>
            <nav class="navbar fixed-top navbar-light bg-light">
                <div class="container-fluid justify-content-center">
                    <h4 class="navbar-brand text-center">Paradisaea<span class="d-xs-none d-sm-none d-md-inline"> - The IoT Picture Frame</span></h4>
                </div>
            </nav>
        </header>
        <div class="container" style="margin-top: 75px">
            <div class="row">
                <div class="col-12">
                    <?= $content ?>
                </div>
            </div>
        </div>
        <footer>
        </footer>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>