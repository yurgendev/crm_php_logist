<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\FilterHelper;

/** @var yii\web\View $this */
/** @var app\models\Lot $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $statuses */
/** @var array $customers */
/** @var array $warehouses */
/** @var array $companies */

$this->title = 'All Lots';
?>
<div class="site-all-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/all-lots']) ?>" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Type VIN, Lot or Auto" name="search" value="<?= Html::encode(Yii::$app->request->get('search')) ?>">
            <!-- Скрытые поля для сохранения фильтров -->
            <?php foreach (Yii::$app->request->get() as $key => $value): ?>
                <?php if (!in_array($key, ['search', 'page'])): ?>
                    <?= Html::hiddenInput($key, $value) ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        Status
                        <?= FilterHelper::renderFilterForm('status', $searchModel->status, ['' => 'All'] + $statuses, Url::to(['site/all-lots'])) ?>
                    </th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>
                        Company
                        <?= FilterHelper::renderFilterForm('company_id', $searchModel->company_id, ['' => 'All'] + ArrayHelper::map($companies, 'id', 'name'), Url::to(['site/all-lots'])) ?>
                    </th>
                    <th>
                        Customer
                        <?= FilterHelper::renderFilterForm('customer_id', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), Url::to(['site/all-lots'])) ?>
                    </th>
                    <th>
                        Warehouse
                        <?= FilterHelper::renderFilterForm('warehouse_id', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), Url::to(['site/all-lots'])) ?>
                    </th>
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
                        <td><?= Html::encode($lot->status) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
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
    <?= $this->render('//partials/_pagination', ['pagination' => $dataProvider->pagination]) ?>
</div>