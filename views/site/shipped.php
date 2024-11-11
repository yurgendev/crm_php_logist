<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $customers */
/** @var array $warehouses */
/** @var array $companies */

$this->title = 'Shipped Lots';
?>
<div class="site-shipped-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/shipped']) ?>" class="mb-3">
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
                    <th>Data Container</th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>LOT</th>
                    <th>Account</th>
                    <th>
                        Customer
                        <?= Html::beginForm(['site/shipped'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[customer_id]', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>
                        Warehouse
                        <?= Html::beginForm(['site/shipped'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[warehouse_id]', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>
                        Company
                        <?= Html::beginForm(['site/shipped'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[company_id]', $searchModel->company_id, ['' => 'All'] + ArrayHelper::map($companies, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>Container</th>
                    <th>Line</th>
                    <th>Booking Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->getModels() as $lot): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDate($lot->date_container) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= Html::encode($lot->account->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Html::encode($lot->container) ?></td>
                        <td><?= Html::encode($lot->line) ?></td>
                        <td><?= Html::encode($lot->booking_number) ?></td>
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