<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\FilterHelper;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $auctions */
/** @var array $customers */
/** @var array $warehouses */

$this->title = 'New Lots';
?>
<div class="site-new-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/new']) ?>" class="mb-3">
        <div class="input-group">
            <?= Html::activeTextInput($searchModel, 'search', ['class' => 'form-control', 'placeholder' => 'Type VIN, Lot or Auto']) ?>
            <?= Html::hiddenInput('LotSearch[status]', $searchModel->status) ?>
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>VIN</th>
                    <th>Lot Number</th>
                    <th>Auto</th>
                    <th>Customer</th>
                    <th>Warehouse</th>
                    <th>Company</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->getModels() as $lot): ?>
                    <tr>
                        <td><?= Html::encode($lot->status) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($lot->price) ?></td>
                        <td>
                            <?= Html::a('<i class="fas fa-edit"></i>', ['site/update-lot', 'id' => $lot->id], ['class' => 'btn btn-outline-primary btn-sm', 'title' => 'Update']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Пагинация -->
    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
    ]) ?>
</div>