<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

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
/** @var string $photoA_filter */
/** @var string $photoD_filter */
/** @var string $photoW_filter */
/** @var string $photoL_filter */

$this->title = 'All Lots';

// Функция для рендеринга формы фильтра
function renderFilterForm($name, $selectedValue, $options, $excludeFields = []) {
    $hiddenFields = [
        'search' => Yii::$app->request->get('search'),
        'status' => Yii::$app->request->get('status'),
        'customer_id' => Yii::$app->request->get('customer_id'),
        'warehouse_id' => Yii::$app->request->get('warehouse_id'),
        'company_id' => Yii::$app->request->get('company_id'),
        'photoA_filter' => Yii::$app->request->get('photoA_filter'),
        'photoD_filter' => Yii::$app->request->get('photoD_filter'),
        'photoW_filter' => Yii::$app->request->get('photoW_filter'),
        'photoL_filter' => Yii::$app->request->get('photoL_filter'),
    ];

    // Убираем текущий фильтр из скрытых полей
    foreach ($excludeFields as $excludeField) {
        unset($hiddenFields[$excludeField]);
    }

    $form = Html::beginForm(['site/all-lots'], 'get', ['class' => 'd-inline']);
    foreach ($hiddenFields as $fieldName => $fieldValue) {
        $form .= Html::hiddenInput($fieldName, $fieldValue);
    }
    $form .= Html::dropDownList($name, $selectedValue, $options, [
        'class' => 'form-select form-select-sm',
        'onchange' => 'this.form.submit()',
    ]);
    $form .= Html::endForm();
    return $form;
}
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
                        <?= renderFilterForm('status', $selectedStatus, ['' => 'All'] + $statuses, ['status']) ?>
                    </th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>
                        Company
                        <?= renderFilterForm('company_id', $selectedCompany, ['' => 'All'] + ArrayHelper::map($companies, 'id', 'name'), ['company_id']) ?>
                    </th>
                    <th>
                        Customer
                        <?= renderFilterForm('customer_id', $selectedCustomer, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), ['customer_id']) ?>
                    </th>
                    <th>
                        Warehouse
                        <?= renderFilterForm('warehouse_id', $selectedWarehouse, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), ['warehouse_id']) ?>
                    </th>
                    <th>
                        Photo A
                        <?= renderFilterForm('photoA_filter', $photoA_filter, [
                            '' => 'All',
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ], ['photoA_filter']) ?>
                    </th>
                    <th>
                        Photo D
                        <?= renderFilterForm('photoD_filter', $photoD_filter, [
                            '' => 'All',
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ], ['photoD_filter']) ?>
                    </th>
                    <th>
                        Photo W
                        <?= renderFilterForm('photoW_filter', $photoW_filter, [
                            '' => 'All',
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ], ['photoW_filter']) ?>
                    </th>
                    <th>
                        Photo L
                        <?= renderFilterForm('photoL_filter', $photoL_filter, [
                            '' => 'All',
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ], ['photoL_filter']) ?>
                    </th>
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
                        <td><?= $lot->photo_a ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'a'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_d ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'd'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_w ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'w'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->photo_l ? Html::a('<i class="fas fa-check"></i>', ['site/gallery', 'id' => $lot->id, 'type' => 'l'], ['target' => '_blank']) : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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