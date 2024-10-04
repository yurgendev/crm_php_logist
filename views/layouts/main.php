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
    <!-- Подключение Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/all-lots']) ?>"><span class="fas fa-car"></span><span class="ps-3 name">All cars</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-plus-circle"></span><span class="ps-3 name">New</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-truck"></span><span class="ps-3 name">Dispatched</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-warehouse"></span><span class="ps-3 name">Terminal</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-box"></span><span class="ps-3 name">Loading</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-ship"></span><span class="ps-3 name">Shipped</span></a></li>
            <li><a class="nav-link" href="#"><span class="fas fa-clipboard-check"></span><span class="ps-3 name">Unloaded</span></a></li>
        </ul>
        <div id="topnavbar">
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