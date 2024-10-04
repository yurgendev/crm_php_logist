<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var app\models\Lot $lot */

$this->title = 'View Photos';
?>
<div class="site-view-photos">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="main-photo">
        <img src="<?= Url::to('@web/uploads/photo_a/' . Html::encode($lot->photo_a)) ?>" alt="Main Photo" class="img-fluid">
    </div>

    <div class="photo-gallery">
        <div class="row">
            <?php if ($lot->photo_a && file_exists(Yii::getAlias('@webroot/uploads/photo_a/' . $lot->photo_a))): ?>
                <div class="col-md-3">
                    <a href="<?= Url::to('@web/uploads/photo_a/' . Html::encode($lot->photo_a)) ?>" target="_blank">
                        <img src="<?= Url::to('@web/uploads/photo_a/' . Html::encode($lot->photo_a)) ?>" alt="Photo A" class="img-thumbnail">
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($lot->photo_d && file_exists(Yii::getAlias('@webroot/uploads/photo_d/' . $lot->photo_d))): ?>
                <div class="col-md-3">
                    <a href="<?= Url::to('@web/uploads/photo_d/' . Html::encode($lot->photo_d)) ?>" target="_blank">
                        <img src="<?= Url::to('@web/uploads/photo_d/' . Html::encode($lot->photo_d)) ?>" alt="Photo D" class="img-thumbnail">
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($lot->photo_w && file_exists(Yii::getAlias('@webroot/uploads/photo_d-w/' . $lot->photo_w))): ?>
                <div class="col-md-3">
                    <a href="<?= Url::to('@web/uploads/photo_d-w/' . Html::encode($lot->photo_w)) ?>" target="_blank">
                        <img src="<?= Url::to('@web/uploads/photo_d-w/' . Html::encode($lot->photo_w)) ?>" alt="Photo W" class="img-thumbnail">
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($lot->photo_l && file_exists(Yii::getAlias('@webroot/uploads/photo_l/' . $lot->photo_l))): ?>
                <div class="col-md-3">
                    <a href="<?= Url::to('@web/uploads/photo_l/' . Html::encode($lot->photo_l)) ?>" target="_blank">
                        <img src="<?= Url::to('@web/uploads/photo_l/' . Html::encode($lot->photo_l)) ?>" alt="Photo L" class="img-thumbnail">
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>