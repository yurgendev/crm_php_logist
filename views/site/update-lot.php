<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customer;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Update: ' . $model->vin;
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['all-lots']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="lot-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'customer_id')->dropDownList(
            ArrayHelper::map(Customer::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Customer']
        ) ?>

        <?= $form->field($model, 'container')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>