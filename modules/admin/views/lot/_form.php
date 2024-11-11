<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $accounts */
/** @var array $customers */
/** @var array $companies */
/** @var array $auctions */
/** @var array $warehouses */
/** @var array $statuses */

?>

<div class="lot-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'auto')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'date_purchase')->input('date') ?>
    <?= $form->field($model, 'date_dispatch')->input('date') ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <br>
    <br>
    <?= $form->field($model, 'account_id')->dropDownList($accounts, ['prompt' => 'Выберите аккаунт']) ?>
    <?= $form->field($model, 'auction_id')->dropDownList($auctions, ['prompt' => 'Выберите аукцион']) ?>
    <?= $form->field($model, 'customer_id')->dropDownList($customers, ['prompt' => 'Выберите клиента']) ?>
    <?= $form->field($model, 'company_id')->dropDownList($companies, ['prompt' => 'Выберите компанию']) ?>
    <?= $form->field($model, 'warehouse_id')->dropDownList($warehouses, ['prompt' => 'Выберите склад']) ?>
    <br>
    <br>
    <?= $form->field($model, 'bosFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'photoAFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'photoDFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'photoWFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'videoFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'titleFiles[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'photoLFiles[]')->fileInput(['multiple' => true]) ?>
    <br>
    <br>
    <?= $form->field($model, 'status')->dropDownList($statuses, ['prompt' => 'Выберите статус']) ?>
    <?= $form->field($model, 'status_changed')->input('date') ?>
    <?= $form->field($model, 'date_warehouse')->input('date') ?>
    <?= $form->field($model, 'payment_date')->input('date') ?>
    <?= $form->field($model, 'date_booking')->input('date') ?>
    <?= $form->field($model, 'data_container')->input('date') ?>
    <?= $form->field($model, 'date_unloaded')->input('date') ?>
    <?= $form->field($model, 'has_keys')->checkbox() ?>
    <?= $form->field($model, 'line')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'booking_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'container')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'ata_data')->input('date') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>