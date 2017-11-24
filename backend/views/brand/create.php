<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Brand */

$this->title = 'สร้างยี่ห้อสินค้า';
$this->params['breadcrumbs'][] = ['label' => 'ยี่ห้อสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
