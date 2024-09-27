<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Lot $model */
/** @var array $accounts */
/** @var array $customers */
/** @var array $companies */
/** @var array $auctions */

$this->title = 'Create Lot';
$this->params['breadcrumbs'][] = ['label' => 'Lots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'accounts' => $accounts,
        'customers' => $customers,
        'companies' => $companies,
        'auctions' => $auctions,
    ]) ?>

</div>