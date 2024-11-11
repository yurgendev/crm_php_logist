<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Customer;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */

$this->title = 'Update Lot: ' . $model->vin;
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['all-lots']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Кнопка Deep Update -->
    <div class="text-right">
        <?= Html::a('Deep Update', ['deep-update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
    </div>

    <div class="lot-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>

        <?= $form->field($model, 'customer_id')->dropDownList(
            ArrayHelper::map(Customer::find()->all(), 'id', 'name'),
            ['prompt' => 'Select Customer']
        ) ?>

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
                'browseLabel' => 'Выбрать BOS',
                'removeLabel' => 'Удалить',
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
                        'showPreview' => true,
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