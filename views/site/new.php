<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\FilterHelper;

/** @var yii\web\View $this */
/** @var app\models\Lot $searchModel */
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
                    <th>Date Purchase</th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>LOT</th>
                    <th>Account</th>
                    <th>
                        Auction
                        <?= FilterHelper::renderFilterForm('auction_id', $searchModel->auction_id, ['' => 'All'] + ArrayHelper::map($auctions, 'id', 'name'), Url::to(['site/new'])) ?>
                    </th>
                    <th>
                        Customer
                        <?= FilterHelper::renderFilterForm('customer_id', $searchModel->customer_id, ['' => 'All'] + ArrayHelper::map($customers, 'id', 'name'), Url::to(['site/new'])) ?>
                    </th>
                    <th>BOS</th>
                    <th>
                        Warehouse
                        <?= FilterHelper::renderFilterForm('warehouse_id', $searchModel->warehouse_id, ['' => 'All'] + ArrayHelper::map($warehouses, 'id', 'name'), Url::to(['site/new'])) ?>
                    </th>
                    <th>Company</th>
                    <th>Price</th>
                    <th>Photo A</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataProvider->getModels() as $lot): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDate($lot->date_purchase) ?></td>
                        <td><?= Html::encode($lot->auto) ?></td>
                        <td><?= Html::encode($lot->vin) ?></td>
                        <td><?= Html::encode($lot->lot) ?></td>
                        <td><?= Html::encode($lot->account->name) ?></td>
                        <td><?= Html::encode($lot->auction->name) ?></td>
                        <td><?= Html::encode($lot->customer->name) ?></td>
                        <td>
                            <?= $lot->bos ? Html::a('<i class="fas fa-download"></i>', ['site/view-pdf', 'id' => $lot->id, 'type' => 'bos'], ['target' => '_blank']) : '' ?>
                        </td>
                        <td><?= Html::encode($lot->warehouse->name) ?></td>
                        <td><?= Html::encode($lot->company->name) ?></td>
                        <td><?= Yii::$app->formatter->asCurrency($lot->price) ?></td>
                        <td>
                            <?php $photoACount = $lot->getPhotoAFileCount(); ?>
                            <?= $photoACount > 0 ? Html::a('<span class="photo-count-circle">' . $photoACount . '</span>', ['site/gallery', 'id' => $lot->id, 'type' => 'a'], ['target' => '_blank']) : '' ?>
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