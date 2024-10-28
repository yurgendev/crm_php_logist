<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Carousel;

/* @var $this yii\web\View */
/* @var $images array */
/* @var $thumbnails array */
/* @var $lot app\models\Lot */
/* @var $type string */

$this->title = 'Photo - ' . strtoupper($type);
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container">
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= Url::to('@web/' . $image) ?>" class="d-block w-100" alt="Image">
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="row mt-4">
        <?php foreach ($thumbnails as $index => $thumbnail): ?>
            <div class="col-md-2">
                <div class="thumbnail-container">
                    <a href="#" data-bs-target="#carouselExample" data-bs-slide-to="<?= $index ?>">
                        <img src="<?= Url::to('@web/' . $thumbnail['path']) ?>" class="img-thumbnail" alt="Thumbnail">
                    </a>
                    <div class="thumbnail-info">
                        <span class="thumbnail-date"><?= date('Y-m-d H:i:s', $thumbnail['uploaded_at']) ?></span>
                        <?= Html::beginForm(['site/delete-image'], 'post', [
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить это изображение?',],]) ?>
                            <?= Html::hiddenInput('id', $lot->id) ?>
                            <?= Html::hiddenInput('type', $type) ?>
                            <?= Html::hiddenInput('image', $images[$index]) ?>
                            <?= Html::submitButton('<i class="fas fa-trash-alt"></i>', ['class' => 'thumbnail-delete']) ?>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.thumbnail-container {
    position: relative;
    text-align: center;
    margin-bottom: 20px; /* Добавим отступ снизу */
}

.thumbnail-info {
    margin-top: 5px; /* Добавим отступ сверху */
    background: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 5px;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.thumbnail-date {
    margin-right: 10px; /* Добавим отступ справа */
}

.thumbnail-delete {
    color: red;
    cursor: pointer;
}
</style>