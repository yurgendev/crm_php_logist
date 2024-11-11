<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // Core infa
            'status',
            'auto',
            'vin',
            'lot',
            'price',
            'has_keys',
            // 'status_changed',

            // DATES:
            'date_purchase',
            'date_dispatch',
            'date_warehouse',
            'payment_date',
            'date_booking',
            'data_container',
            'date_unloaded',
            'ata_data',


            // 'account_id',
            'account.name',
            // 'auction_id',
            'auction.name',
            // 'customer_id',
            'customer.name',
            // 'warehouse_id',
            'warehouse.name',
            // 'company_id',
            'company.name',
            'url:url',

            'line',
            'booking_number',
            'container',

            //  files:
            [
                'attribute' => 'bos',
                'value' => $model->getBosFileCount() > 0 ? $model->getBosFileCount() . ' files' : '',
                'label' => 'Bos Files',
            ],
            [
                'attribute' => 'photo_a',
                'value' => $model->getPhotoAFileCount() > 0 ? $model->getPhotoAFileCount() . ' files' : '',
                'label' => 'Photo A Files',
            ],
            [
                'attribute' => 'photo_d',
                'value' => $model->getPhotoDFileCount() > 0 ? $model->getPhotoDFileCount() . ' files' : '',
                'label' => 'Photo D Files',
            ],
            [
                'attribute' => 'photo_w',
                'value' => $model->getPhotoWFileCount() > 0 ? $model->getPhotoWFileCount() . ' files' : '',
                'label' => 'Photo W Files',
            ],
            [
                'attribute' => 'video',
                'value' => $model->getVideoFileCount() > 0 ? $model->getVideoFileCount() . ' files' : '',
                'label' => 'Video Files',
            ],
            [
                'attribute' => 'title',
                'value' => $model->getTitleFileCount() > 0 ? $model->getTitleFileCount() . ' files' : '',
                'label' => 'Title Files',
            ],
            [
                'attribute' => 'photo_l',
                'value' => $model->getPhotoLFileCount() > 0 ? $model->getPhotoLFileCount() . ' files' : '',
                'label' => 'Photo L Files',
            ],
        ],
    ]) ?>

</div>