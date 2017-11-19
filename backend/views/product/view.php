<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <p>รายละเอียดสินค้า</p>
                </div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                         //   'id',
                            'product_code',
                            'name',
                            'description',
                            'photo',
                            //'category_id',
                            [
                              'attribute'=>'category_id',
                              'value'=>function($data){
                                return \backend\models\Category::getCategorycode($data->category_id);
                              }
                            ],
                           // 'weight',
                             [
                              'attribute'=>'unit_id',
                              'value'=>function($data){
                                return \backend\models\Unit::getUnitname($data->unit_id);
                              }
                            ],
                            'qty',
                            'cost',
                            'price',
                            [
                               'attribute'=>'status',
                               'format' => 'html',
                               'value'=>function($data){
                                 return $data->status === 1 ? '<div class="label label-success">Active</div>':'<div class="label label-default">Inactive</div>';
                               }
                             ],
                            [
                               'attribute'=>'created_at',
                               'value'=>function($data){
                                 return date('d-m-Y',$data->created_at);
                               }
                             ],
                            // 'updated_at',
                            // 'created_by',
                            // 'updated_by',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <p>สถานะสินค้า</p>
                </div>
                <div class="box-body">
                </div>
            </div>
        </div>
    </div>
</div>
