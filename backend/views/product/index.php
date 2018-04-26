<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use dosamigos\multiselect\MultiSelect;
use backend\assets\ICheckAsset;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use backend\common\cart;
use lavrentiev\widgets\toastr\Notification;

ICheckAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ผลิตภัณฑ์';
$this->params['breadcrumbs'][] = $this->title;

$xsale = intval($sale_sum);
$xpurch = intval($purch_sum);

$modelx = $dataProvider->query->all();
$modellist = '';
if($modelx){
  $x =0;
  foreach($modelx as $data){
    if($x==0){
      $modellist.=$data->id;
    }elseif($x==count($modelx)){
      $modellist.=','.$data->id;
    }else{
      $modellist.= ','.$data->id;
    }
    $x+=1;
  }
}
$session = Yii::$app->session;
//unset($session['cart']);

$this->registerCss('
    #example-selectedClass-container{
      background-color: red;
    }
  '
);
// $cart = new cart();
// $cart->addCart(100,['prodid'=>1,'qty'=>1]);
// if(isset($session['cart'])){
//                      $infocart = $session['cart'];
//                     // echo $infocart[100]['product_id'];
//                      //print_r($infocart);
//                      foreach($infocart as $key => $data){
//                         echo $key.' '.$data['product_id'];
//                      }
//                    }else{
//                     //return 50;
//                    }

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

$groupall = \backend\models\Category::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
$typeall = \backend\models\Producttype::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
$brandall = \backend\models\Brand::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
$vendorall = \backend\models\Vendor::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
$propertyall = \backend\models\Property::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
$modeall = [['id'=>1,'name'=>'สั่งซื้อ'],['id'=>0,'name'=>'ไม่สั่งซื้อ']];
$movementall = [['id'=>1,'name'=>'เคลื่อนไหว'],['id'=>2,'name'=>'ไม่เคลื่อนไหว']];
if($product_type !='' && $group !=''){
  $typeall = \backend\models\Producttype::find()->where(['group_id'=>$group])->orderby(['name'=>SORT_ASC])->all();
}
if($property !=''){
  $propertyall = \backend\models\Property::find()->where(['type_id'=>$product_type])->orderby(['name'=>SORT_ASC])->all();
  if(!$propertyall){
    $propertyall = \backend\models\Property::find()->where(['!=','name',''])->orderby(['name'=>SORT_ASC])->all();
  }
}else{

}

$this->registerJsFile(
    '@web/js/stockbalancejs.js?V=001',
    ['depends' => [\yii\web\JqueryAsset::className()]],
    static::POS_END
);

?>

<?php $session = Yii::$app->session;
      if ($session->getFlash('msg_addcart')): ?>
       <!-- <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <?php //echo $session->getFlash('msg'); ?>
      </div> -->
        <?php echo Notification::widget([
            'type' => 'success',
            'title' => 'แจ้งผลการทำงาน',
            'message' => $session->getFlash('msg_addcart'),
            'options' => [
                "closeButton" => false,
                "debug" => false,
                "newestOnTop" => false,
                "progressBar" => false,
                "positionClass" => "toast-bottom-center",
                "preventDuplicates" => false,
                "onclick" => null,
                "showDuration" => "300",
                "hideDuration" => "1000",
                "timeOut" => "6000",
                "extendedTimeOut" => "1000",
                "showEasing" => "swing",
                "hideEasing" => "linear",
                "showMethod" => "fadeIn",
                "hideMethod" => "fadeOut"
            ]
        ]); ?> 
        <?php endif; 
  ?>
<div class="product-index">
  <div class="row">
    <div class="col-lg-12">
      ข้อมูลอัพเดทล่าสุด <i class="fa fa-calendar-o"> </i> <?= date('d-m-Y',$last_update)?>
    </div>
  </div>
  <input type="hidden" name="listid" class="listid" value="">
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

   
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
           <div>
            <?= Html::a('<i class="fa fa-plus-circle"></i> สร้างผลิตภัณฑ์', ['create'], ['class' => 'btn btn-success']) ?>
            <div class="btn btn-default btn-import" data-toggle="modal" data-target="#myModal"><i class="fa fa-upload"></i> นำเข้าสินค้า</div>
            <div class="btn btn-info btn-import-update" data-toggle="modal" data-target="#myModal_update"><i class="fa fa-upload"></i> อัพเดทข้อมูลสินค้า</div>
             <div class="btn btn-danger btn-bulk-remove" disabled><i class="fa fa-trash"></i> ลบ <span class="remove_item">[0]</span></div>
             <div class="btn btn-danger btn-all-remove"><i class="fa fa-trash"></i> ลบทั้งหมด <span ></span></div>
             
             <div class="btn btn-warning btn-view" disabled><i class="fa fa-eye"></i> รายละเอียด </div>
             <div class="btn btn-primary btn-update"><i class="fa fa-pencil"></i> แก้ไข </div>
             <div class="btn btn-default btn-print"> <i class="fa fa-print"></i> พิมพ์</div>
             <!-- <div class="btn btn-default btn-po"> <i class="fa fa-shopping-cart"></i> สร้างใบสั่งซื้อ</div> -->
             <div class="btn btn-default btn-pick"> <i class="fa fa-shopping-cart"></i> หยิบใส่ตะกร้า</div>
             <div class="btn btn-default btn-chart"> <i class="fa fa-pie-chart"></i> กราฟเปรียบเทียบซื้อขาย</div>
            <div class="btn-group pull-right" style="bottom: 10px">
        <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
      </div>
      </div>
      <div class="panel-body">
        <?php //Pjax::begin(['id'=>'my-pjax']); ?>
        <div class="row">
          <div class="col-lg-12">
            <form id="search-form" action="<?=Url::to(['product/index'],true)?>" method="post">
              <input type="hidden" class="actiontype" name="action-type" value="0">
              <input type="hidden" class="sort_report" name="sort_report" value="">
        <!--    <form id="search-form" action="index.php?r=product" method="get"> -->
                   <div class="form-inline">
                    
                    <input type="text" name="code_search" class="form-control code_search" value="<?=$code_search?>" placeholder="รหัสสินค้า">
                       <input type="text" name="text_search" class="form-control text_search" value="<?=$text_search?>" placeholder="ชื่อสินค้า">
                    <?php      echo MultiSelect::widget([
                            'id'=>"product_group",
                            'name'=>'product_group[]',
                            //'model'=>null,
                            "options" => ['multiple'=>"multiple",
                                            'onchange'=>''], // for the actual multiselect
                            'data' => count($groupall)==0?['No Data']:ArrayHelper::map($groupall,'id','name'), // data as array
                            'value' => $group, // if preselected
                            "clientOptions" => 
                                [
                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 5,
                                    'nonSelectedText'=>'กลุ่มสินค้า',
                                    'enableFiltering' => true,
                                    'enableCaseInsensitiveFiltering'=>true,
                                ], 
                        ]); ?>
                   
             
                 <?php      echo MultiSelect::widget([
                           // 'id'=>"multiXX",
                            'name'=>'type',
                            'id'=>"product_type",
                            //'model'=>null,
                            "options" => ['multiple'=>"multiple"], // for the actual multiselect
                            'data' => count($typeall)==0?['No Data']:ArrayHelper::map($typeall,'id','name'), // data as array
                            'value' => $product_type, // if preselected
                            "clientOptions" => 
                                [
                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 5,
                                    'nonSelectedText'=>'ประเภทสินค้า',
                                    'enableFiltering' => true,
                                    'disabled' => 'disabled',
                                    'enableCaseInsensitiveFiltering'=>true,
                                ], 
                        ]); ?>
                <?php      echo MultiSelect::widget([
                        'id'=>"property",
                        'name'=>'property[]',
                        //'model'=>null,
                        "options" => ['multiple'=>"multiple",
                              //"disabled"=>"disabled"
                        ], // for the actual multiselect
                        'data' => count($typeall)==0?['No Data']:ArrayHelper::map($propertyall,'id','name'), // data as array
                        'value' => $property, // if preselected
                        "clientOptions" => 
                            [
                                "includeSelectAllOption" => true,
                                'numberDisplayed' => 5,
                                'nonSelectedText'=>'ลักษณะ',
                                'enableFiltering' => true,
                                'enableCaseInsensitiveFiltering'=>true,
                            ], 
                    ]); ?>
               
                 <?php      echo MultiSelect::widget([
                            'id'=>"vendor",
                            'name'=>'vendor[]',
                           // 'id'=>"vendor",
                            //'model'=>null,
                            "options" => ['multiple'=>"multiple",
                                    //"disabled"=>"disabled"
                            ], // for the actual multiselect
                            'data' => count($vendorall)==0?['No Data']:ArrayHelper::map($vendorall,'id','name'), // data as array
                            'value' => $vendor, // if preselected
                            "clientOptions" => 
                                [
                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 5,
                                    'nonSelectedText'=>'ผู้จำหน่าย',
                                    'enableFiltering' => true,
                                    'enableCaseInsensitiveFiltering'=>true,
                                ], 
                        ]); ?>

                         <?php      echo MultiSelect::widget([
                            'id'=>"movement_id",
                            'name'=>'movement',
                            //'id'=>"vendor",
                            //'model'=>null,
                            "options" => ['multiple'=>"multiple",
                                    //"disabled"=>"disabled"
                            ], // for the actual multiselect
                            'data' => count($movementall)==0?['การเคลื่อนไหว']:ArrayHelper::map($movementall,'id','name'), // data as array
                            'value' => $movement, // if preselected
                            "clientOptions" => 
                                [
                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 5,
                                    'nonSelectedText'=>'การเคลื่อนไหว',
                                    'enableFiltering' => true,
                                    'enableCaseInsensitiveFiltering'=>true,
                                ], 
                        ]); ?>

                        <?php      echo MultiSelect::widget([
                            'id'=>"sale_mode",
                            'name'=>'mode',
                            //'id'=>"vendor",
                            //'model'=>null,
                            "options" => ['multiple'=>"multiple",
                                    //"disabled"=>"disabled"
                            ], // for the actual multiselect
                            'data' => count($modeall)==0?['เลือกโหมดสั่งซื้อ']:ArrayHelper::map($modeall,'id','name'), // data as array
                            'value' => $mode, // if preselected
                            "clientOptions" => 
                                [
                                    "includeSelectAllOption" => true,
                                    'numberDisplayed' => 5,
                                    'nonSelectedText'=>'เลือกโหมดสั่งซื้อ',
                                    'enableFiltering' => true,
                                    'enableCaseInsensitiveFiltering'=>true,
                                ], 
                        ]); ?>
               
               

         
          <?php      echo MultiSelect::widget([
              'id'=>"brand",
              'name'=>'brand[]',
              //'model'=>null,
              "options" => ['multiple'=>"multiple",
                              //"disabled"=>"disabled"
                            
                           ], // for the actual multiselect
              'data' => count($brandall)==0?['No Data']:ArrayHelper::map($brandall,'id','name'), // data as array
              'value' => $brand, // if preselected
             // 'name' => 'multti', // name for the form
              "clientOptions" => 
                  [
                      "includeSelectAllOption" => true,
                      'numberDisplayed' => 5,
                      'nonSelectedText'=>'ยี่ห้อ',
                      'enableFiltering' => true,
                      'enableCaseInsensitiveFiltering'=>true,
                  ], 
          ]); ?>

               
                <!-- <input type="submit" class="btn btn-primary" value="ค้นหา"> -->
               <div class="btn btn-primary btn-search">ค้นหา</div>
               <div class="btn btn-warning btn-reset">รีเซ็ต</div>
               <div class="btn btn-info btn-hide-col"><i class="fa  fa-eye-slash"></i> ซ่อนคอลัมน์ <span ></span></div>
            </div>
            </form>
       
          </div>
        </div><br />
<div class="table-grid">
 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'product-grid',
        //'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table-striped'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],

            //'id',
           
            'product_code',
            //'name',
            [
              'attribute'=>'name',
              'format'=>'raw',
              'value'=>function($data){
                  if($data->notes!=''){
                    return '<div onclick="shownote('.$data->id.');" class="show_note" style="color: red;cursor: pointer">'.$data->name.'</div>';
                  }else{
                    return '<div>'.$data->name.'</div>';
                  }
              }
            ],
             [
              'attribute'=>'unit_id',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return \backend\models\Unit::getUnitname($data->unit_id);
              }
             ],
            //'description',
           // 'category_id',
            //'photo',
              [
               'attribute'=>'category_id',
               'headerOptions'=>['class'=>'head_cat'],
               'contentOptions'=>['class'=>'content_cat'],
               'format' => 'html',
               'value'=>function($data){
                 return $data->category_id !== Null ? \backend\models\Category::getCategorycode($data->category_id):'';
               }
             ],
              [
              'attribute'=>'type_id',
              'headerOptions'=>['class'=>'head_type'],
              'contentOptions'=>['style'=>'text-align: left','class'=>'content_type'],
              'value' => function($data){
                return \backend\models\Producttype::getTypename($data->type_id);
              }
             ],
              [
              'attribute'=>'property_id',
              'headerOptions'=>['class'=>'head_prop'],
              'contentOptions'=>['style'=>'text-align: left','class'=>'content_prop'],
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
              [
              'attribute'=>'sale_price',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                return $data->sale_price == 0?'-':number_format($data->sale_price);
              }
             ],
              [
              'attribute'=>'mode',
              'label'=>'สั่งซื้อ',
              'format' => 'html',
              'value'=> function($data){
                return $data->mode == 1?"<h4><i class='fa fa-check-circle text-success'></i></h4>":"<h4><i class='fa fa-ban text-danger'></i></h4>";
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
              'contentOptions'=>['style'=>'text-align: right;'],
              'format'=>'raw',
              'value' => function($data){
               // return number_format($data->sale_qty);
                if($data->sale_qty <= 0){
                  return '<div class="text-red">-</div>';
                }else{
                  return '<div class="text-red"><b>'.number_format($data->sale_qty).'</b></div>';
                }
                
              }
             ],
              [
              'attribute'=>'purch_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'format'=>'raw',
              'value' => function($data){
                if($data->purch_qty <= 0){
                  return '<div class="text-green">-</div>';
                }else{
                  return '<div class="text-green"><b>'.number_format($data->purch_qty).'</b></div>';
                }
                
              }
             ],
              [
              'attribute'=>'qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'format'=>'raw',
              'value' => function($data){
                 if($data->qty <= 0){
                  return '-';
                }else{
                  return number_format($data->qty);
                }
              }
             ],
              [
              'attribute'=>'return_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                if($data->return_qty <= 0){
                  return '-';
                }else{
                  return number_format($data->return_qty);
                }
              }
             ],
             [
              'attribute'=>'adjust_qty',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                if($data->adjust_qty <= 0){
                  return '-';
                }else{
                  return number_format($data->adjust_qty);
                }
              }
             ],
            
             [
              'attribute'=>'cost_sum',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                 if($data->cost_sum <= 0){
                  return '-';
                }else{
                  return number_format($data->cost_sum);
                }
              }
             ],
              [
              'attribute'=>'cost',
              'contentOptions'=>['style'=>'text-align: right'],
              'value' => function($data){
                 if($data->cost <= 0){
                  return '-';
                }else{
                  return number_format($data->cost);
                }
              }
             ],
            
              [
              'attribute'=>'vendor_id',
              'contentOptions'=>['style'=>'text-align: left'],
              'value' => function($data){
                return \backend\models\Vendor::getVendorname($data->vendor_id);
              }
             ],
             // [
             //  'attribute'=>'updated_at',
             //  'label'=>'อัพเดทล่าสุด',
             //  'contentOptions'=>['style'=>'text-align: center'],
             //  'value' => function($data){
             //    return date('d-m-Y',$data->updated_at);
             //  }
             // ],
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

            // [
            //             'label' => 'Action',
            //             'format' => 'raw',
            //             'value' => function($model){
            //                     return '
            //                         <div class="btn-group" >
            //                             <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
            //                             <ul class="dropdown-menu" style="right: 0; left: auto;">
            //                             <li><a href="'.Url::toRoute(['/product/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
            //                             <li><a href="'.Url::toRoute(['/product/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
            //                             </ul>
            //                         </div>
            //                     ';
            //                 // }
            //             },
            //             'headerOptions'=>['class'=>'text-center'],
            //             'contentOptions' => ['class'=>'text-center','style'=>'vertical-align: middle','text-align: center'],

            //         ],
        ],
        'containerOptions' => ['style'=>'overflow: auto'],
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => false,
        'resizableColumnsOptions' => ['resizeFromBody' => true],
        'hover' => true,
         'floatHeader'=>true,
        // 'floatOverflowContainer' => true,
         'floatHeaderOptions'=>[
              'scrollingTop'=>'50',
              'zIndex'=>900,
              'floatThead'=>[
                'position'=>'auto',
                'autoReflow'=>true,
              ],
          'floatContainerClass'=>'table-grid',
          ],
        // 'panel' => [
        //     'type' => GridView::TYPE_PRIMARY
        // ],
    ]); ?>
  <!--   </div> -->
    </div>
  </div>
  </div>
  </div>
    <?php //Pjax::end(); ?>
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
<div id="myModal_update" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-download"></i> อัพเดทข้อมูลสินค้า</h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
           <div class="col-lg-12">
                <?php 

                ActiveForm::begin(['action'=>Url::to(['product/importupdate'],true),'options'=>['enctype'=>'multipart/form-data']]);
                echo FileInput::widget([
                  'name' => 'file',
                  'model' => $modelfile2,
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
<div id="myModal_po" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
  <?php
     $title_pop = '';
     $session = Yii::$app->session;
     if(isset($session['purchase_id'])){
      $title_pop = "เพิ่มสินค้าใบสั่งซื้อเลขที่ ".$session['purchase_no'];
     }else{
      $title_pop = "สร้างใบสั่งซื้อ";
     }
  ?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-shopping-cart"></i> <?=$title_pop?> <small id="items"> </small></h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-lg-12">
             <form id="form-po" action="<?=Url::to(['product/genpo'],true)?>" method="post">
              <input type="hidden" name="listid" class="listid" value="">
              <?= Select2::widget([
                 'data'=> ArrayHelper::map(\backend\models\Vendor::find()->all(),'id','name'),
                 'name' => 'vendor_id',
                 'options'=>[],
              ]);?>
              <br>
              <input type="submit" value="ตกลง" class="btn btn-primary">
             </form>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>
<div id="myModal_note" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-commenting"></i> บันทึก <small id="items"> </small></h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-lg-12">
             <p class="note_text"></p>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>
<div id="myModal_cart" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
   <?php
     $title_pop = '';
     $session = Yii::$app->session;
     if(isset($session['purchase_id'])){
      $title_pop = "เพิ่มสินค้าใบสั่งซื้อเลขที่ ".$session['purchase_no'];
     }else{
      $title_pop = "สร้างใบสั่งซื้อ";
     }
  ?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-shopping-cart"></i> <?=$title_pop?> <small id="items"> </small></h4>
      </div>
      <div class="modal-body">
        
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</div>
<div id="myModal_chart" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-pie-chart text-warning"></i> กราฟเปรียบเทียบซื้อขาย <small id="items"> </small></h4>
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-lg-12">
              <?php
               // $sale_sum = $sale_sum;

             

                $titlename = ['ซื้อ', 'ขาย',];
                $dataamt = [['ซื้อ', $xpurch], ['ขาย', $xsale]];
                echo Highcharts::widget([
                    'options' => [
                        'title' => ['text' => ''],
                        'xAxis' => [
                            'categories' => $titlename
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'มูลค่าซื้อขาย']
                        ],
                        'series' => [
                            ['name' => 'Qty', 'data' => $dataamt],

                        ],
                        'legend'=>[
                          'enabled'=> true,
                          'useHtml'=> true,
                        // 'labelFormatter' => new JsExpression('function () { return this.name + this.data[0] }'),
                        ],
                        'colors' => ['#1aadce', '#FF6633'],
                        'credits' => ['enabled' => false],
                        'chart' => [
                            'type' => 'pie',
                            'options3d' => [
                                'enabled' => 'true',
                                'alpha' => 45,
                            ],
                        ],
                        'tooltip' => [
                            'pointFormat' => '{series.name}: <b>{point.y:.1f} Qty.</b>'
                        ],
                        'plotOptions' => [
                            'pie' => [
                                'allowPointSelect' => true,
                                'cursor' => 'pointer',
                                'dataLabels' => [
                                    'enabled' => false,
                                    'format' => '<b>{point.name}</b>: {point.percentage:.1f} %',
                                    'style' => [
                                        //'color'=> (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                    ]
                                ],
                                'showInLegend' => true,
                                
                            ]
                        ],
                    ]
                ]);
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
<?php 

  $url_to_delete =  Url::to(['product/bulkdelete'],true);
   $url_to_deleteall =  Url::to(['product/alldelete'],true);
  $url_to_showreport =  Url::to(['product/showreport'],true);
  $url_to_index_search =  Url::to(['product/index'],true);
  $url_to_update =  Url::to(['product/update2'],true);
  $url_to_view =  Url::to(['product/view2'],true);
  $url_to_getnote =  Url::to(['product/getnote'],true);
  $url_to_addpick =  Url::to(['purchaseorder/addpick'],true);
  $url_to_showcart =  Url::to(['purchaseorder/showitem'],true);

  $this->registerJs('
     
    
    $(function(){
      var serc = "'.count($product_type).'";
      var perty = "'.count($property).'";


      
         if($("#product_group").val()!=""){
            $("#product_group").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "กลุ่มสินค้า"
            });
            $("select#product_group").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
          if($("#product_type").val()!=""){
            $("#product_type").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "ประเภทสินค้า"
            });
            $("select#product_type").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
            $("select#product_type").prop("disabled","");
          }
          if($("#movement_id").val()!=""){
            $("#movement_id").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "การเคลื่อนไหว"
            });
            $("select#movement_id").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
          if($("#vendor").val()!=""){
            $("#vendor").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "ผู้จำหน่าย"
            });
            $("select#vendor").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
          if($("#brand").val()!=""){
            $("#brand").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "ยี่ห้อ"
            });
            $("select#brand").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
          if($("#sale_mode").val()!=""){
            $("#sale_mode").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "เลือกโหมดสั่งซื้อ"
            });
            $("select#sale_mode").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
          if($("#property").val()!=""){
            $("#property").multiselect({
               includeSelectAllOption: true,
               enableFiltering: true,
               nonSelectedText: "ลักษณะ"
            });
            $("select#property").parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"}); 
          }
 

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
              //$("#product_type").prop("disabled","");
              $("#product_type").html(data);
               $("#product_type").multiselect("rebuild");
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
             // $("#property").prop("disabled","");
              $("#property").html(data);
              $("#property").multiselect("rebuild");
            }
          });
        }

            
                   if($(this).val()!=""){
                        $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                   }else{
                       $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
                
               
      });

      $("#property").change(function(){
        if($(this).val()!=""){
          var grp = $("#product_group").val();
          var typ = $("#product_type").val();

          $.ajax({
            type: "post",
            dataType: "html",
            url: "'.Url::to(['product/showvendor'],true).'",
            data: {groupid:grp,typeid:typ,propertyid: $(this).val()},
            success: function(data){
             // $("#property").prop("disabled","");
              $("#vendor").html(data);
              $("#vendor").multiselect("rebuild");
            }
          });
        }
            if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
      });
      $("#vendor").change(function(){
        if($(this).val()!=""){
          var grp = $("#product_group").val();
          var typ = $("#product_type").val();
          var prop = $("#property").val();

          $.ajax({
            type: "post",
            dataType: "html",
            url: "'.Url::to(['product/showbrand'],true).'",
            data: {groupid:grp,typeid:typ,propertyid:prop,vendorid: $(this).val()},
            success: function(data){
             // $("#property").prop("disabled","");
              $("#brand").html(data);
              $("#brand").multiselect("rebuild");
            }
          });
        }
             if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
      });

    });
    $("#movement_id").change(function(){
            if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
    });
    $("#sale_mode").change(function(){
             if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
    });
     $("#brand").change(function(){
             if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
    });
    $(".btn-bulk-remove").click(function(e){
          //alert($(".listid").val());
                if($(this).attr("disabled")){
                  return;
                }
                if(confirm("คุณต้องการลบรายการที่เลือกใช่หรือไม่")){
                  if($(".listid").length >0){
                    $.ajax({
                      type: "post",
                      dataType: "html",
                      url: "'.$url_to_delete.'",
                      data: {id: $(".listid").val()},
                      success: function(data){

                      }
                    });
                  }
                }
    });
     $(".btn-all-remove").click(function(e){
               
                if(confirm("คุณต้องการลบรายการทั้งหมดใช่หรือไม่")){
                    var datax;
                    datax = "'.$modellist.'";
                    $.ajax({
                      type: "post",
                      dataType: "html",
                      url: "'.$url_to_deleteall.'",
                      data: {id: datax },
                      success: function(data){
                          //alert(data);
                      }
                    });

                    // $("#search-form").attr("action","");
                    // $("#search-form").attr("target","_parent");
                    // $("#search-form").attr("action","'.$url_to_deleteall.'");
                    // $("#search-form").submit();
                }
    });

    $(".btn-view").click(function(e){
          //alert($(".listid").val());
                if($(this).attr("disabled")){
                  return;
                }
                
                  if($(".listid").length >0){
                    $.ajax({
                      type: "post",
                      dataType: "html",
                      url: "'.$url_to_view.'",
                      data: {id: $(".listid").val()},
                      success: function(data){

                      }
                    });
                  }
              
    });

    $(".btn-print").click(function(){

      var strindex = $(location).attr("href").indexOf("sort");
      var sorttext = $(location).attr("href").substr(strindex + 5,$(location).attr("href").length);
     
      if(strindex > 0){
         $(".sort_report").val(sorttext);
      }
     

      $("#search-form").attr("action","");
      $("#search-form").attr("target","_blank");
      $("#search-form").attr("action","'.$url_to_showreport.'");
      $("#search-form").submit();
    });
    $(".btn-search").click(function(){
      $("#search-form").attr("action","");
      $("#search-form").attr("target","_parent");
      $("#search-form").attr("action","'.$url_to_index_search.'");
      $("#search-form").submit();
    });
    
    $(".btn-po").click(function(){
      $("#myModal_po").modal("show");
    });
   $(".btn-chart").click(function(){
      $("#myModal_chart").modal("show");
    });
   $(".btn-update").click(function(){
      if($(".listid").length >0){
                    $.ajax({
                      type: "post",
                      dataType: "html",
                      url: "'.$url_to_update.'",
                      data: {id: $(".listid").val()},
                      success: function(data){
  
                      }
                    });
                  }else{
        alert("เลือกรายการก่อน");
      }
   });

   $("select#product_group").change(function(){
       if($(this).val()!=""){
                   $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"gray","color":"#FFF"});
                  }else{
                    $(this).parent().find(".btn-group").find(".multiselect").css({"background-color":"#F5F5F5","color":"#000"});
            }
   });



   $("div.btn-reset").click(function(){

        $(".text_search").val("");
        $(".code_search").val("");
        $("select#product_group option:selected").remove();
        $("select#product_group").multiselect("rebuild");

        $("select#product_type option:selected").remove();
        $("select#product_type").multiselect("rebuild");

        $("select#property option:selected").remove();
        $("select#property").multiselect("rebuild");

        $("select#vendor option:selected").remove();
        $("select#vendor").multiselect("rebuild");

         $("select#brand option:selected").remove();
        $("select#brand").multiselect("rebuild");

        $("select#sale_mode option:selected").remove();
        $("select#sale_mode").val("เลือกโหมดสั่งซื้อ").change();

         $("select#movement_id option:selected").remove();
        $("select#movement_id").val("การเคลื่อนไหว").change();

        $(".btn-search").trigger("click");
   });

   $("div.btn-hide-col").click(function(){
      if($(".head_cat:visible").length == 0){
         $(".head_cat").show();
         $(".content_cat").show();
         $(".head_type").show();
         $(".content_type").show();
         $(".head_prop").show();
         $(".content_prop").show();
      }else{
         $(".head_cat").hide();
         $(".content_cat").hide();
          $(".head_type").hide();
         $(".content_type").hide();
         $(".head_prop").hide();
         $(".content_prop").hide();
      }
     
   });

   $(".btn-pick").click(function(){
      var cnt = $(".listid").val().split(",");
      var listid = $(".listid").val();
       if(cnt.length > 0){
          $.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_addpick.'",
                  data: {ids: listid},
                  success: function(data){
                    // alert(data);
                    $(".cnt-pick").text(data);
                    location.reload();
                  }
        });
       }

   });
   $(".show-pick").click(function(){
       // $.ajax({
       //            type: "post",
       //            dataType: "html",
       //            url: "'.$url_to_showcart.'",
       //            data: {ids: 0},
       //            success: function(data){
                   
       //            }
       //  });
       $("#myModal_cart").modal("show").find(".modal-body").load("'.$url_to_showcart.'");
   });


   function shownote(id){
     if(id != ""){
        $.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_getnote.'",
                  data: {ids: id},
                  success: function(data){
                    //alert(data);
                    $("#myModal_note").modal("show").find(".note_text").text(data);
                  }
        });
     }
   }

  ',static::POS_END);?>