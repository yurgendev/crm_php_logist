<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="px-0 bg-light">
    <div class="d-flex">
        <div class="d-flex align-items-center" id="navbar">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-items" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
                <span class="fas fa-bars"></span>
            </button>
            <a class="text-decoration-none fs14 ps-2" href="#">ACQUIRED<span class="fs13 pe-2">.com</span></a>
        </div>
        <div id="navbar2" class="d-flex justify-content-end pe-4">
            <span class="far fa-user-circle"></span>
        </div>
    </div>
    <div class="d-md-flex">
        <ul id="navbar-items" class="p-0">
            <li><span class="fas fa-th-list"></span><span class="ps-3 name">All cars</span></li>
            <li><span class="fas fa-chart-line"></span><span class="ps-3 name">New</span></li>
            <li><span class="fas fa-clipboard-check"></span><span class="ps-3 name">Dispatched</span></li>
            <li><span class="fas fa-suitcase-rolling"></span><span class="ps-3 name">Terminal</span></li>
            <li><span class="fas fa-calendar-alt"></span><span class="ps-3 name">Loading</span></li>
            <li><span class="fas fa-comment-alt"></span><span class="ps-3 name">Shipped</span></li>
            <li><span class="fas fa-store-alt"></span><span class="ps-3 name">Unloaded</span></li>
        </ul>
        <div id="topnavbar">
            
            <div class="d-flex align-items-center mb-3 px-md-3 px-2">
                <span class="text-uppercase fs13 fw-bolder pe-3">search<span class="ps-1">by</span></span>
                <form class="example d-flex align-items-center">
                    <input type="text" placeholder="Type VIN, Lot or Auto" name="search">
                    <button type="submit">↵<i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="table-responsive px-2">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <span class="text-muted">&copy; My Yii Application <?= date('Y') ?></span>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>