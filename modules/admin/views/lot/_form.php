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

?>

<div class="lot-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'auto')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'date_purchase')->input('date') ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    

    <?= $form->field($model, 'account_id')->dropDownList($accounts, ['prompt' => 'Выберите аккаунт']) ?>
    <?= $form->field($model, 'auction_id')->dropDownList($auctions, ['prompt' => 'Выберите аукцион']) ?>
    <?= $form->field($model, 'customer_id')->dropDownList($customers, ['prompt' => 'Выберите клиента']) ?>
    <?= $form->field($model, 'company_id')->dropDownList($companies, ['prompt' => 'Выберите компанию']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>