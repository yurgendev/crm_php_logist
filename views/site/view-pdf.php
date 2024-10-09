<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $pdfFile string */
/* @var $lot app\models\Lot */
/* @var $type string */

$this->title = 'Documentation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-view-pdf">
    <h1><?= Html::encode($this->title) ?></h1>
    <embed src="<?= Yii::getAlias('@web') . '/' . $pdfFile ?>" type="application/pdf" width="100%" height="600px" />
</div>