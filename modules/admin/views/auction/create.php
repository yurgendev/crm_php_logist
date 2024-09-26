<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Auction $model */

$this->title = 'Create Auction';
$this->params['breadcrumbs'][] = ['label' => 'Auctions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
