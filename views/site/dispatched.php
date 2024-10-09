<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
/** @var app\models\Lot[] $lots */
/** @var yii\data\Pagination $pagination */
/** @var string $search */

$this->title = 'Dispatched Lots';
?>
<div class="site-dispatched-lots">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Форма поиска -->
    <form method="get" action="<?= Url::to(['site/dispatched']) ?>" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Type VIN, Lot or Auto" name="search" value="<?= Html::encode($search) ?>">
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Auto</th>
                    <th>VIN</th>
                    <th>Company</th>
                    <th>Customer</th>
                    <th>Warehouse</th>
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
                    <?php if ($lot->status == 'dispatched'): ?>
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
                    <?php endif; ?>
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