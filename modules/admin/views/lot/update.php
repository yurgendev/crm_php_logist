<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */
/** @var array $accounts */
/** @var array $customers */
/** @var array $companies */
/** @var array $auctions */
/** @var array $warehouses */

$this->title = 'Update Lot: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lot-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'accounts' => $accounts,
        'customers' => $customers,
        'companies' => $companies,
        'auctions' => $auctions,
        'warehouses' => $warehouses,
        'statuses' => $statuses,
    ]) ?>

</div>