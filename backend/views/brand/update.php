<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */

$this->title = 'แก้ไขยี่ห้อ: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'ยี่ห้อ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
