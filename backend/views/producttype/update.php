<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Producttype */

$this->title = 'แก้ไขประเภทสินค้า: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'ประเภทสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="producttype-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
