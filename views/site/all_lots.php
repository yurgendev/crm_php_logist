<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Lot[] $lots */
/** @var yii\data\Pagination $pagination */
/** @var string $search */
/** @var array $statuses */
/** @var string $selectedStatus */
/** @var array $customers */
/** @var string $selectedCustomer */
/** @var array $warehouses */
/** @var string $selectedWarehouse */
/** @var array $companies */
/** @var string $selectedCompany */

$this->title = 'All Lots';
?>
<div class="site-all-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="mb-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Type VIN, Lot or Auto" name="search" value="<?= Html::encode($search) ?>">
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status
                        <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="d-inline">
                            <select class="form-select form-select-sm" name="status" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($statuses as $key => $value): ?>
                                    <option value="<?= Html::encode($key) ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>><?= Html::encode($value) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>
                        Company
                        <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="d-inline">
                            <select class="form-select form-select-sm" name="company_id" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= Html::encode($company->id) ?>" <?= $selectedCompany == $company->id ? 'selected' : '' ?>><?= Html::encode($company->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </th>
                    <th>
                        Customer
                        <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="d-inline">
                            <select class="form-select form-select-sm" name="customer_id" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= Html::encode($customer->id) ?>" <?= $selectedCustomer == $customer->id ? 'selected' : '' ?>><?= Html::encode($customer->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </th>
                    <th>
                        Warehouse
                        <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="d-inline">
                            <select class="form-select form-select-sm" name="warehouse_id" onchange="this.form.submit()">
                                <option value="">All</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= Html::encode($warehouse->id) ?>" <?= $selectedWarehouse == $warehouse->id ? 'selected' : '' ?>><?= Html::encode($warehouse->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </th>
                    <th>LOT</th>
                    <th>Keys</th>
                    <th>BOS</th>
                    <th>Title</th>
                    <th>Photo A</th>
                    <th>Photo D</th>
                    <th>Photo W</th>
                    <th>Photo L</th>
                    <th>Video</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lots as $lot): ?>
                    <tr>
                        <td><?= Html::encode($lot->status) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= $lot->has_keys ? '<i class="fas fa-check"></i>' : '' ?></td>                        
                        <td><?= $lot->bos ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'bos'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->title ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'title'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_a ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'a'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_d ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'd'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_w ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'w'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_l ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'l'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->video ? '<i class="fas fa-check"></i>' : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Пагинация -->
    <div class="pagination-wrapper">
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkOptions' => ['class' => 'page-link'],
            'pageCssClass' => 'page-item',
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'disabled',
        ]) ?>
    </div>
</div>