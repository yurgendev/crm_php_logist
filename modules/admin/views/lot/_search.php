<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lot-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bos') ?>

    <?= $form->field($model, 'photo_a') ?>

    <?= $form->field($model, 'photo_d') ?>

    <?= $form->field($model, 'photo_w') ?>

    <?php // echo $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'photo_l') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_changed') ?>

    <?php // echo $form->field($model, 'date_purchase') ?>

    <?php // echo $form->field($model, 'date_warehouse') ?>

    <?php // echo $form->field($model, 'payment_date') ?>

    <?php // echo $form->field($model, 'date_booking') ?>

    <?php // echo $form->field($model, 'data_container') ?>

    <?php // echo $form->field($model, 'date_unloaded') ?>

    <?php // echo $form->field($model, 'auto') ?>

    <?php // echo $form->field($model, 'vin') ?>

    <?php // echo $form->field($model, 'lot') ?>

    <?php // echo $form->field($model, 'account_id') ?>

    <?php // echo $form->field($model, 'auction_id') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'warehouse_id') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'has_keys') ?>

    <?php // echo $form->field($model, 'line') ?>

    <?php // echo $form->field($model, 'booking_number') ?>

    <?php // echo $form->field($model, 'container') ?>

    <?php // echo $form->field($model, 'ata_data') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
