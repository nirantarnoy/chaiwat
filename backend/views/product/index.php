<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
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

$groupall = \backend\models\Category::find()->where(['!=','name',''])->all();
$typeall = \backend\models\Producttype::find()->where(['!=','name',''])->all();
$brandall = \backend\models\Brand::find()->where(['!=','name',''])->all();
$vendorall = \backend\models\Vendor::find()->where(['!=','name',''])->all();
$propertyall = \backend\models\Property::find()->where(['!=','name',''])->all();
$modeall = [['id'=>1,'name'=>'สั่งซ์้อ'],['id'=>0,'name'=>'ไม่สั่งซ์้อ']];
if($product_type !=''){
  $typeall = \backend\models\Producttype::find()->where(['group_id'=>$group])->all();
}
if($property !=''){
  $propertyall = \backend\models\Property::find()->where(['type_id'=>$product_type])->all();
}
//print_r($product_type);return;

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
        <div class="row">
          <div class="col-lg-12">
            <form id="search-form" action="<?=Url::to(['product/index'],true)?>" method="post">
                   <div class="form-inline">
                    
                    <input type="text" name="text_search" class="form-control" value="<?=$text_search?>" placeholder="รหัสสินค้า,ชื่อสินค้า">
                    <div class="form-group">
                        <?php echo Select2::widget([
                        'name'=>'product_group[]',
                        'id'=>"product_group",
                        'value'=>$group,
                        'data' => ArrayHelper::map(\backend\models\Category::find()->all(),'id','name'),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'กลุ่มสินค้า','multiple' => true,'class'=>'form-control'],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10,
                        ],
                      ]);
                    ?>

                    </div>
               <!--  <select class="form-control" id="product_group" name="product_group">
                  <option value="">เลือกกลุ่มสินค้า</option>
                  <?php foreach($groupall as $value):?>
                  <?php $select = '';
                    if($value->id == $group){
                      $select = 'selected';
                    }
                  ?>
                  <option value="<?=$value->id?>" <?=$select?>><?=$value->name?></option>
                 <?php endforeach;?>
                </select> -->
                <!--  <select class="form-control" id="product_type" name="type" disabled>
                  <option value="">เลือกประเภทสินค้า</option>
                  <?php foreach($typeall as $value):?>
                  <?php $select = '';
                    if($value->id == $product_type){
                      $select = 'selected';
                    }
                  ?>
                  <option value="<?=$value->id?>" <?=$select?>><?=$value->name?></option>
                 <?php endforeach;?>
                </select> -->
                <div class="form-group">
                        <?php echo Select2::widget([
                        'name'=>'type',
                        'id'=>"product_type",
                        'value'=>$product_type,
                        'data' => ArrayHelper::map($typeall,'id','name'),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'เประเภทสินค้า','multiple' => true,'class'=>'form-control'],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10,
                        ],
                      ]);
                    ?>

                    </div>
                 <!-- <select class="form-control" id="property" name="property" disabled>
                  <option value="">เลือกคุณสมบัติ</option>
                  <?php foreach($propertyall as $value):?>
                  <?php $select = '';
                    if($value->id == $property){
                      $select = 'selected';
                    }
                  ?>
                  <option value="<?=$value->id?>" <?=$select?>><?=$value->name?></option>
                 <?php endforeach;?>
                </select> -->
                <div class="form-group">
                        <?php echo Select2::widget([
                        'name'=>'property[]',
                        'id'=>"property",
                        'value'=>$property,
                        'data' => ArrayHelper::map($propertyall,'id','name'),
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'คุณสมบัติ','multiple' => true,'class'=>'form-control'],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10,
                        ],
                      ]);
                    ?>

                    </div>
                 <!-- <select class="form-control" name="brand">
                  <option value="">เลือกยี่ห้อสินค้า</option>
                  <?php foreach($brandall as $value):?>
                  <?php $select = '';
                    if($value->id == $brand){
                      $select = 'selected';
                    }
                  ?>
                  <option value="<?=$value->id?>" <?=$select?>><?=$value->name?></option>
                 <?php endforeach;?>
                </select> -->
               <div class="form-group">
                        <?php echo Select2::widget([
                        'name'=>'brand[]',
                        'id'=>"brand",
                        'data' => ArrayHelper::map($brandall,'id','name'),
                        'value'=>$brand,
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'ยี่ห้อ','multiple' => true,'class'=>'form-control'],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10,
                        ],
                      ]);
                    ?>

                    </div>
                 <!-- <select class="form-control" name="vendor">
                  <option value="">เลือกผู้จำหน่าย</option>
                  <?php foreach($vendorall as $value):?>
                  <?php $select = '';
                    if($value->id == $vendor){
                      $select = 'selected';
                    }
                  ?>
                  <option value="<?=$value->id?>" <?=$select?>><?=$value->name?></option>
                 <?php endforeach;?>
                </select> -->
                <div class="form-group">
                        <?php echo Select2::widget([
                        'name'=>'vendor[]',
                        'id'=>"vendor",
                        'data' => ArrayHelper::map($vendorall,'id','name'),
                        'value'=>$vendor,
                        'maintainOrder' => true,
                        'options' => ['placeholder' => 'ผู้จำหน่าย','multiple' => true,'class'=>'form-control'],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10,
                        ],
                      ]);
                    ?>
                    </div>
                <select class="form-control" name="mode">
                  <option value="">เลือกโหมดสั่งซื้อ</option>
                  <?php for($i=0;$i<=count($modeall)-1;$i++):?>
                  <?php $select = '';
                  if($mode != ''):?>
                  <?php
                    if($modeall[$i]['id'] == $mode){
                      $select = 'selected';
                    } ?>
                     <option value="<?=$modeall[$i]['id']?>" <?=$select?>><?=$modeall[$i]['name']?></option>
                  <?php else:?>
                     <option value="<?=$modeall[$i]['id']?>" <?=$select?>><?=$modeall[$i]['name']?></option>
                  <?php 
                    endif;
                  ?>
                 
                 <?php endfor;?>
                </select>
               
                <input type="submit" class="btn btn-primary" value="ค้นหา">
            </div>
            </form>
       
          </div>
        </div><br />
<div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
              'attribute'=>'mode',
              'label'=>'สั่งซื้อ',
              'format' => 'html',
              'value'=> function($data){
                return $data->mode == 1?"<i class='fa fa-check-circle text-success'></i>":"<i class='fa fa-ban text-danger'></i>";
              }
            ],
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
              [
              'attribute'=>'type_id',
              'contentOptions'=>['style'=>'text-align: left'],
              'value' => function($data){
                return \backend\models\Producttype::getTypename($data->type_id);
              }
             ],
              [
              'attribute'=>'property_id',
              'contentOptions'=>['style'=>'text-align: left'],
              'value' => function($data){
                return \backend\models\Property::getPropertyname($data->property_id);
              }
             ],
              [
              'attribute'=>'brand_id',
              'contentOptions'=>['style'=>'text-align: left'],
              'value' => function($data){
                return \backend\models\Brand::getBrandname($data->brand_id);
              }
             ],
            // 'weight',
            
             //  [
             //  'attribute'=>'unit_id',
             //  'contentOptions'=>['style'=>'text-align: right'],
             //  'value' => function($data){
             //    return \backend\models\Unit::getUnitname($data->unit_id);
             //  }
             // ],
             
             // [
             //  'attribute'=>'product_start',
             //  'contentOptions'=>['style'=>'text-align: right'],
             //  'value' => function($data){
             //    return number_format($data->product_start);
             //  }
             // ],
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
             [
              'attribute'=>'sale_price',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return number_format($data->sale_price);
              }
             ],
              [
              'attribute'=>'vendor_id',
              'contentOptions'=>['style'=>'text-align: left'],
              'value' => function($data){
                return \backend\models\Vendor::getVendorname($data->vendor_id);
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
<?php $this->registerJs('
    $(function(){
      var serc = "'.count($product_type).'";
      var perty = "'.count($property).'";
      if(serc !=""){
        $("#product_type").prop("disabled","");
      }
       if(perty !=""){
        $("#property").prop("disabled","");
      }
      $("#product_group").change(function(){
        if($(this).val()!=""){
          $.ajax({
            type: "post",
            dataType: "html",
            url: "'.Url::to(['product/showtype'],true).'",
            data: {ids: $(this).val()},
            success: function(data){
              $("#product_type").prop("disabled","");
              $("#product_type").html(data);
            }
          });
        }
      });
      
      $("#product_type").change(function(){
        if($(this).val()!=""){
          $.ajax({
            type: "post",
            dataType: "html",
            url: "'.Url::to(['product/showproperty'],true).'",
            data: {ids: $(this).val()},
            success: function(data){
              $("#property").prop("disabled","");
              $("#property").html(data);
            }
          });
        }
      });

    });
  ',static::POS_END);?>