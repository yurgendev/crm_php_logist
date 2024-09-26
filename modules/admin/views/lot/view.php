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
            'id',
            'bos',
            'photo_a',
            'photo_d',
            'photo_w',
            'video',
            'title',
            'photo_l',
            'status',
            'status_changed',
            'date_purchase',
            'date_warehouse',
            'payment_date',
            'date_booking',
            'data_container',
            'date_unloaded',
            'auto',
            'vin',
            'lot',
            'account_id',
            'auction_id',
            'customer_id',
            'warehouse_id',
            'company_id',
            'url:url',
            'price',
            'has_keys',
            'line',
            'booking_number',
            'container',
            'ata_data',
        ],
    ]) ?>

</div>
