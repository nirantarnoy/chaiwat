<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ผลิตภัณฑ์';
$this->params['breadcrumbs'][] = $this->title;

// $events = array();
//   //Testing
//   $Event = new \yii2fullcalendar\models\Event();
//   $Event->id = 1;
//   $Event->title = 'Testing';
//   $Event->start = date('Y-m-d\TH:i:s\Z');
//   // $event->nonstandard = [
//   //   'field1' => 'Something I want to be included in object #1',
//   //   'field2' => 'Something I want to be included in object #2',
//   // ];
//   $events[] = $Event;

//   $Event = new \yii2fullcalendar\models\Event();
//   $Event->id = 2;
//   $Event->title = 'Testing';
//   $Event->start = date('Y-m-d\TH:i:s\Z',strtotime('tomorrow 6am'));
//   $events[] = $Event;


//   echo \yii2fullcalendar\yii2fullcalendar::widget(array(
//       'events'=> $events,
//   ));


?>
<div class="product-index">
   <div class="row">
    <div class="col-lg-12">
      <?php
      $session = Yii::$app->session;
       if(!empty($session->getFlash("success"))):?>
      <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <?php echo $session->getFlash('success'); ?>
      </div>
    <?php endif;?>
    <?php if(!empty($session->getFlash("error"))):?>
    <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <?php echo $session->getFlash('error'); ?>
      </div>
    <?php endif;?>
    </div>
   </div>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
           <div>
            <?= Html::a('<i class="fa fa-plus-circle"></i> สร้างผลิตภัณฑ์', ['create'], ['class' => 'btn btn-success']) ?>
            <div class="btn btn-default btn-import" data-toggle="modal" data-target="#myModal"><i class="fa fa-upload"></i> นำเข้าสินค้า</div>
            <div class="btn-group pull-right" style="bottom: 10px">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
      </div>
      </div>
      <div class="panel-body">
<div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'product_code',
          //  'name',
            'description',
           // 'category_id',
            //'photo',
              [
               'attribute'=>'category_id',
               'format' => 'html',
               'value'=>function($data){
                 return $data->category_id !== Null ? \backend\models\Category::getCategorycode($data->category_id):'';
               }
             ],
            // 'weight',
             [
              'attribute'=>'unit_id',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return \backend\models\Unit::getUnitname($data->unit_id);
              }
             ],
             
             [
              'attribute'=>'product_start',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->product_start);
              }
             ],
             [
              'attribute'=>'sale_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->sale_qty);
              }
             ],
              [
              'attribute'=>'purch_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->purch_qty);
              }
             ],
              [
              'attribute'=>'return_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->return_qty);
              }
             ],
             [
              'attribute'=>'adjust_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->adjust_qty);
              }
             ],
             [
              'attribute'=>'qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->qty);
              }
             ],
             [
              'attribute'=>'cost_sum',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->cost_sum);
              }
             ],
              [
              'attribute'=>'cost',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->cost);
              }
             ],
            // 'cost',
            // 'price',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

           // [
           //     'attribute'=>'status',
           //     'format' => 'html',
           //     'value'=>function($data){
           //       return $data->status === 1 ? '<div class="label label-success">Active</div>':'<div class="label label-default">Inactive</div>';
           //     }
           //   ],
            //'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            [
                        'label' => 'Action',
                        'format' => 'raw',
                        'value' => function($model){
                                return '
                                    <div class="btn-group" >
                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                                        <ul class="dropdown-menu" style="right: 0; left: auto;">
                                        <li><a href="'.Url::toRoute(['/product/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
                                        <li><a href="'.Url::toRoute(['/product/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
                                        <li><a onclick="return confirm(\'Confirm ?\')" href="'.Url::to(['/product/delete', 'id'=>$model->id],true).'">Delete</a></li>
                                        </ul>
                                    </div>
                                ';
                            // }
                        },
                        'headerOptions'=>['class'=>'text-center'],
                        'contentOptions' => ['class'=>'text-center','style'=>'vertical-align: middle','text-align: center'],

                    ],
        ],
    ]); ?>
    </div>
    </div>
  </div>
  </div>
  </div>
    <?php Pjax::end(); ?>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-download"></i> นำเข้ารหัสสินค้า</h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
            <div class="col-lg-12">
                <?php 

                ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);
                echo FileInput::widget([
                  'name' => 'file',
                  'model' => $modelfile,
                  'attribute' => 'file',

                ]);
                ?>
                <br />
                <div class="btn-group">
                  <input type="submit" class="btn btn-success" value="ตกลง">
                </div>
                <?php
                ActiveForm::end();
             ?>
            </div>
           
        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>
