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

$this->title = 'Archived Lots';
?>
<div class="site-archived-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/archived']) ?>" class="mb-3">
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
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>Company</th>
                    <th>
                        Customer
                        <?= Html::beginForm(['site/archived'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[customer_id]', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>
                        Warehouse
                        <?= Html::beginForm(['site/archived'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[warehouse_id]', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>LOT</th>
                    <th>
                        Keys
                        <?= Html::beginForm(['site/archived'], 'get', ['class' => 'filter-form']) ?>
                        <?= Html::dropDownList('LotSearch[has_keys]', $searchModel->has_keys, ['' => 'All', '1' => 'Yes', '0' => 'No'], [
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                        ]) ?>
                        <?= Html::endForm() ?>
                    </th>
                    <th>BOS</th>
                    <th>Title</th>
                    <th>Photo A</th>
                    <th>Photo D</th>
                    <th>Photo W</th>
                    <th>Photo L</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->getModels() as $lot): ?>
                    <tr>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= $lot->has_keys ? 'Yes' : 'No' ?></td>
                        <td><?= $lot->bos ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'bos'], ['target' => '_blank']) : '' ?></td>
                        <td><?= $lot->title ? Html::a('<i class="fas fa-check"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'title'], ['target' => '_blank']) : '' ?></td>
                        <td>
                            <?php $photoACount = $lot->getPhotoAFileCount(); ?>
                            <?= $photoACount > 0 ? Html::a('<span class="photo-count-circle">' . $photoACount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'a'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoDCount = $lot->getPhotoDFileCount(); ?>
                            <?= $photoDCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoDCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'd'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoWCount = $lot->getPhotoWFileCount(); ?>
                            <?= $photoWCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoWCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'w'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td>
                            <?php $photoLCount = $lot->getPhotoLFileCount(); ?>
                            <?= $photoLCount > 0 ? Html::a('<span class="photo-count-circle">' . $photoLCount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'l'], ['target' => '_blank']) : '' ?>
                        </td>   
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