<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Producttype */

$this->title = 'ประเภทสินค้า';
$this->params['breadcrumbs'][] = ['label' => 'ปรเภทสินค้า', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="producttype-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
