<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lot-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_a')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_d')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_w')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo_l')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'new' => 'New', 'dispatched' => 'Dispatched', 'terminal' => 'Terminal', 'loading' => 'Loading', 'shipped' => 'Shipped', 'unloaded' => 'Unloaded', 'archived' => 'Archived', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status_changed')->textInput() ?>

    <?= $form->field($model, 'date_purchase')->textInput() ?>

    <?= $form->field($model, 'date_warehouse')->textInput() ?>

    <?= $form->field($model, 'payment_date')->textInput() ?>

    <?= $form->field($model, 'date_booking')->textInput() ?>

    <?= $form->field($model, 'data_container')->textInput() ?>

    <?= $form->field($model, 'date_unloaded')->textInput() ?>

    <?= $form->field($model, 'auto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_id')->textInput() ?>

    <?= $form->field($model, 'auction_id')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'warehouse_id')->textInput() ?>

    <?= $form->field($model, 'company_id')->textInput() ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'has_keys')->textInput() ?>

    <?= $form->field($model, 'line')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'booking_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'container')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ata_data')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
