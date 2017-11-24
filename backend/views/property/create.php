<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Property */

$this->title = 'สร้างคุณสมบัติสินค้า';
$this->params['breadcrumbs'][] = ['label' => 'คุณสมบัติสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
