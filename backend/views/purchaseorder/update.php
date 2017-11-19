<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Purchaseorder */

$this->title = 'แก้ไขใบสั่งซื้อ: '.$model->purchase_order;
$this->params['breadcrumbs'][] = ['label' => 'ใบสั่งซื้อ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->purchase_order, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchaseorder-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelline' => $modelline,
    ]) ?>

</div>
