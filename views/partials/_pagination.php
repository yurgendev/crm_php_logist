<?php
use yii\widgets\LinkPager;

/** @var yii\data\Pagination $pagination */
?>

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