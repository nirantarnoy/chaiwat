<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Property */

$this->title = 'แก้ไขคุณสมบัติสินค้า: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'คุณสมบัติสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="property-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
