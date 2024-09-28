<?php

use app\models\Lot;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LotSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lots';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lot', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <!-- поисковик для будушего -->
    <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            

            // 'id',
            // 'bos',
            // 'photo_a',
            // 'photo_d',
            // 'photo_w',
            //'video',
            //'title',
            //'photo_l',
            'status',
            //'status_changed',
            //'date_purchase',
            //'date_warehouse',
            //'payment_date',
            //'date_booking',
            //'data_container',
            //'date_unloaded',
            'auto',
            'vin',
            'lot',
            //'account_id',
            //'auction_id',
            //'customer_id',
            // 'warehouse_id',
            [
                'attribute' => 'warehouse_id',
                'value' => 'warehouse.name',
            ],
            //'company_id',
            //'url:url',
            //'price',
            //'has_keys',
            //'line',
            //'booking_number',
            //'container',
            //'ata_data',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Lot $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
