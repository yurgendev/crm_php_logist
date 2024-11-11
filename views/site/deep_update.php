<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customer;
use app\models\Account;
use app\models\Auction;
use app\models\Company;
use app\models\Warehouse;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */

$this->title = 'Deep Update Lot: ' . $model->vin;
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['all-lots']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Deep Update';
?>
<div class="lot-deep-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="lot-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>

        <?= $form->field($model, 'status')->dropDownList($model::getStatuses(), ['prompt' => 'Select Status']) ?>

        <?= $form->field($model, 'auto')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'vin')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'has_keys')->dropDownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Select']) ?>
        <?= $form->field($model, 'status_changed')->input('date') ?>
        <?= $form->field($model, 'date_purchase')->input('date') ?>
        <?= $form->field($model, 'date_dispatch')->input('date') ?>
        <?= $form->field($model, 'date_warehouse')->input('date') ?>
        <?= $form->field($model, 'payment_date')->input('date') ?>
        <?= $form->field($model, 'date_booking')->input('date') ?>
        <?= $form->field($model, 'date_container')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'date_unloaded')->input('date') ?>
        <?= $form->field($model, 'ata_data')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'date_container')->input('date') ?>
        <?= $form->field($model, 'account_id')->dropDownList(
            ArrayHelper::map(Account::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Account']
        ) ?>
        <?= $form->field($model, 'auction_id')->dropDownList(
            ArrayHelper::map(Auction::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Auction']
        ) ?>
        <?= $form->field($model, 'customer_id')->dropDownList(
            ArrayHelper::map(Customer::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Customer']
        ) ?>
        <?= $form->field($model, 'warehouse_id')->dropDownList(
            ArrayHelper::map(Warehouse::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Warehouse']
        ) ?>
        <?= $form->field($model, 'company_id')->dropDownList(
            ArrayHelper::map(Company::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Company']
        ) ?>
        <?= $form->field($model, 'line')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'booking_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'container')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

        <div class="file-input-container">
            <div class="file-input-item">
                <?= $form->field($model, 'bosFiles')->widget(FileInput::classname(), [
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'initialPreview' => $model->getInitialPreview('bos'),
                        'initialPreviewConfig' => $model->getInitialPreviewConfig('bos'),
                        'initialPreviewAsData' => true,
                        'overwriteInitial' => false,
                        'showUpload' => false,
                        'browseLabel' => 'Choose BOS',
                        'removeLabel' => 'Delete',
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
                        'deleteUrl' => Url::to(['/site/delete-file']),
                        'deleteExtraData' => [
                            'id' => $model->id,
                            'type' => 'bos',
                        ],
                    ],
                ]); ?>
            </div>
            <div class="file-input-item">
                <?= $form->field($model, 'titleFiles')->widget(FileInput::classname(), [
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'initialPreview' => $model->getInitialPreview('title'),
                        'initialPreviewConfig' => $model->getInitialPreviewConfig('title'),
                        'initialPreviewAsData' => true,
                        'overwriteInitial' => false,
                        'showUpload' => false,
                        'browseLabel' => 'Choose Title',
                        'removeLabel' => 'Delete',
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
                    ],
                ]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>