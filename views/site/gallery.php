<?php
/* @var $this yii\web\View */
/* @var $images array */
/* @var $lot app\models\Lot */
/* @var $type string */

$this->title = 'Gallery for Lot ' . $lot->id . ' - ' . strtoupper($type);
?>
<h1>Gallery for Lot <?= $lot->id ?> - <?= strtoupper($type) ?></h1>

<div class="gallery">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <img src="<?= Yii::getAlias('@web') . '/' . $image ?>" alt="Image">
        </div>
    <?php endforeach; ?>
</div>

<style>
.gallery {
    display: flex;
    flex-wrap: wrap;
}
.gallery-item {
    margin: 10px;
}
.gallery-item img {
    max-width: 200px;
    max-height: 200px;
}
</style>