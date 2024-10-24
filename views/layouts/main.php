<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Html;
use app\assets\AppAsset;
use yii\bootstrap5\ActiveForm;

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/css/site.css">
    <link rel="stylesheet" href="<?= Yii::$app->request->baseUrl ?>/css/custom.css">
</head>
<body>
<?php $this->beginBody() ?>

<<div class="container-fluid px-0 bg-light">
    <div class="row no-gutters">
        <div class="col-md-2 d-flex align-items-center justify-content-between sidebar-header" id="navbar">
            <div>
                <a class="text-decoration-none fs14 ps-2" href="#">logic magic<span class="fs13 pe-2">.com</span></a>
            </div>
            <div class="ml-auto logout-form">
                <?php $form = ActiveForm::begin(['action' => ['site/logout'], 'method' => 'post']); ?>
                    <?= Html::submitButton('<span class="far fa-user-circle"></span>', [
                        'class' => 'btn btn-link',
                        'style' => 'padding: 0; border: none; background: none;',
                    ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
        <div class="col-md-10 d-flex justify-content-end pe-4" id="navbar2">
            
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-2" id="sidebar-container">
            <ul id="navbar-items" class="sidebar p-0">
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/all-lots']) ?>"><span class="fas fa-car"></span><span class="ps-3 name">All cars</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/new']) ?>"><span class="fas fa-plus-circle"></span><span class="ps-3 name">New</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/dispatched']) ?>"><span class="fas fa-truck"></span><span class="ps-3 name">Dispatched</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/terminal']) ?>"><span class="fas fa-warehouse"></span><span class="ps-3 name">Terminal</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/loading']) ?>"><span class="fas fa-box"></span><span class="ps-3 name">Loading</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/shipped']) ?>"><span class="fas fa-ship"></span><span class="ps-3 name">Shipped</span></a></li>
                <li><a class="nav-link" href="<?= \yii\helpers\Url::to(['site/unloaded']) ?>"><span class="fas fa-clipboard-check"></span><span class="ps-3 name">Unloaded</span></a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <div class="main-content">
                <?= $content ?>
            </div>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-md-12">
            <footer class="footer mt-auto py-3 bg-light">
                <div class="container">
                    <span class="text-muted">&copy; My Yii Application <?= date('Y') ?></span>
                </div>
            </footer>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
<!-- Подключение Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php $this->endPage() ?>