<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use toxor88\switchery\Switchery;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Product */
/* @var $form yii\widgets\ActiveForm */
$cat = \backend\models\Category::find()->where(['status'=>1])->all();
$sub_cat = \backend\models\Subcategory::find()->where(['status'=>1])->all();
$unit = \backend\models\Unit::find()->where(['status'=>1])->all();
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>


<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">ข้อมูลสินค้า</a></li>
              <?php if(!$model->isNewRecord):?>
              <li><a href="#tab_2" data-toggle="tab">สินค้าคงคลังและความเคลื่อนไหว</a></li>
            <?php endif;?>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                  <div class="row">
                      <div class="col-lg-12">
                        <div class="panel">
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-lg-6">

                                    <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'category_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($cat,'id','name'),
                                                    'options'=>['placeholder' => 'เลือกหมวดผลิตภัณฑ์','class'=>'form-control','id'=>'level',
                                                       'onchange'=>' 
                                                          $.post("index.php?r=product/showsubcategory&id=' . '"+$(this).val(),function(data){
                                                          $("select#sub_cat").html(data);
                                                          $("select#sub_cat").prop("disabled","");

                                                          });
                                                      ',
                                                    ],
                                                    ]

                                                  )->label() ?>
                                    <?= $form->field($model, 'parent_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($sub_cat,'id','name'),
                                                     'options'=>['placeholder' => 'เลือกหมวดผลิตภัณฑ์ย่อย','class'=>'form-control','id'=>'sub_cat','disabled'=>'disabled'],
                                                    ]

                                                  )->label() ?>

                                    <?php //echo $form->field($model, 'brand_id')->widget(Select2::className(),
                                                   // [
                                                    // 'data'=> ArrayHelper::map(\backend\models\Brand::find()->all(),'id','name'),
                                                    /// 'options'=>['placeholder' => 'เลือกยี่ห้อ','class'=>'form-control','id'=>'brand_id',
                                                     //'onchange'=>' 
                                                     //     $.post("index.php?r=product/showmodel&id=' . '"+$(this).val(),function(data){
                                                      //    $("select#model_id").html(data);
                                                      //    $("select#model_id").prop("disabled","");
                                                      //    });
                                                     // ',
                                                   //  ],
                                                   // ]
                                                //  )->label() ?>

                                    <?php //echo $form->field($model, 'model_id')->widget(Select2::className(),
                                             //       [
                                             //        'data'=> ArrayHelper::map(\backend\models\Productmodel::find()->all(),'id','name'),
                                             //        'options'=>['placeholder' => 'เลือกรุ่นสินค้า','class'=>'form-control','id'=>'model_id','disabled'=>'disabled'],
                                              //      ]

                                              //    )->label() ?>

                                    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'unit_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($unit,'id','name'),
                                                     'options'=>['placeholder' => 'เลือกหน่วยนับ','class'=>'form-control','id'=>'unit'],
                                                    ]

                                                  )->label() ?>

                                    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
                                    
                                    <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'min_qty')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'max_qty')->textInput(['maxlength' => true]) ?>

                                    <?php echo "<h5>แนบไฟล์</h5>";?>
                                       <?php
                                           echo FileInput::widget([
                                             'model' => $modelfile,
                                              'attribute' => 'file[]',
                                              'id'=>'upfile',
                                              'options' => ['multiple' => true,'accept' => ['.TXT','.PDF','.PNG','.JPG','.GIF'],'style'=>'width: 300px'],
                                              'pluginOptions' => [
                                              'showUpload'=>false,
                                              'maxFileCount'=>3,
                                                ],
                                              ]);
                                       ?>
                 
                                     <input type="hidden" name="old_photo" value="<?=$model->photo?>" />
                                     <br />
                                   <?php echo $form->field($model, 'status')->widget(Switchery::className(),['options'=>['label'=>'']]) ?>

                                  <div class="form-group">
                                      <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                                  </div>
                                </div>
                                </div>

                                    <hr />
                                    <div class="row">
                                      <div class="col-lg-12">
                                         <?php if(!$model->isNewRecord){
                                              if(count($imagelist)>0){
                                                $list = [];
                                                echo "<div class='row'>";
                                                foreach($imagelist as $value){
                                                  // array_push($list,[
                                                  //     'url' => '@web/uploads/images/'.$value->image,
                                                  //     'src' => '@web/uploads/images/'.$value->image,
                                                  //     'options' =>['title' => 'Sail us to the Moon','style'=>'height: 10px;width:10px;']
                                                  //  ]);
                                                  echo "<div class='col-lg-2' style='padding: 15px;'>";
                                                  echo Html::img('@web/uploads/images/'.$value->image,['style'=>'width: 80%']);
                                                  echo "</div>";
                                                }
                                              echo "</div>";
                                                // $items = [$list];
                                              //echo dosamigos\gallery\Gallery::widget(['items' => $list]);
                                            }
                                           }?>

                                      </div>
                                    </div>

                                </div>
                              </div>
                            </div>
                          </div>
              </div>
              <?php if(!$model->isNewRecord):?>
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-12">
                            <h3>สินค้าคงคลัง</h3>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                              <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                   // 'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                       // 'id',
                                        //'warehouse_id',
                                        [
                                          'attribute'=>'warehouse_id',
                                          'value'=> function($data){
                                            return $data->warehouse_id!=''?\backend\models\Warehouse::findName($data->warehouse_id):'';
                                          }
                                        ],
                                        'qty',
                                        [
                                        'attribute'=>'updated_at',
                                        'label' => 'อัพเดทล่าสุด',
                                          'value' => function($data){
                                            return date('d-m-Y',$data->updated_at);
                                          }
                                        ]
                                        // [
                                        //   'attribute'=>'sale_date',
                                        //   'value' => function($data){
                                        //     return date('d-m-Y',$data->sale_date);
                                        //   }
                                        // ],
                                        //'sale_amount',
                                        //'payment_type',
                                        //'require_ship_date',
                                        //'note',
                                       // 'payment_status',
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
                                        //                             <li><a href="'.Url::toRoute(['/sale/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
                                        //                             <li><a href="'.Url::toRoute(['/sale/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
                                        //                             <li><a onclick="return confirm(\'Confirm ?\')" href="'.Url::to(['/sale/delete', 'id'=>$model->id],true).'">Delete</a></li>
                                        //                             </ul>
                                        //                         </div>
                                        //                     ';
                                        //                 // }
                                        //             },
                                        //             'headerOptions'=>['class'=>'text-center'],
                                        //             'contentOptions' => ['class'=>'text-center','style'=>'vertical-align: middle','text-align: center'],

                                        //         ],
                                    ],
                                ]); ?>
                          </div>
                        </div>
                         <div class="row">
                          <div class="col-lg-12">
                            <h3>ความเคลื่อนไหวล่าสุด</h3>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12">
                              <?= GridView::widget([
                                    'dataProvider' => $dataProvider2,
                                   // 'filterModel' => $searchModel,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],

                                       // 'id',
                                        'journal_no',
                                        [
                                          'attribute'=>'warehouse_id',
                                          'value'=> function($data){
                                            return $data->warehouse_id!=''?\backend\models\Warehouse::findName($data->warehouse_id):'';
                                          }
                                        ],
                                        'qty',
                                        [
                                        'attribute'=>'invent_type',
                                        'format'=>'html',
                                          'value' => function($data){
                                           
                                              return $data->invent_type === 0 ? '<div class="label label-success">in</div>':'<div class="label label-danger">out</div>';
                                          
                                          }
                                        ],
                                        [
                                        'attribute'=>'transdate',
                                          'value' => function($data){
                                            return date('d-m-Y',$data->transdate);
                                          }
                                        ]
                                        // [
                                        //   'attribute'=>'sale_date',
                                        //   'value' => function($data){
                                        //     return date('d-m-Y',$data->sale_date);
                                        //   }
                                        // ],
                                        //'sale_amount',
                                        //'payment_type',
                                        //'require_ship_date',
                                        //'note',
                                       // 'payment_status',
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
                                        //                             <li><a href="'.Url::toRoute(['/sale/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
                                        //                             <li><a href="'.Url::toRoute(['/sale/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
                                        //                             <li><a onclick="return confirm(\'Confirm ?\')" href="'.Url::to(['/sale/delete', 'id'=>$model->id],true).'">Delete</a></li>
                                        //                             </ul>
                                        //                         </div>
                                        //                     ';
                                        //                 // }
                                        //             },
                                        //             'headerOptions'=>['class'=>'text-center'],
                                        //             'contentOptions' => ['class'=>'text-center','style'=>'vertical-align: middle','text-align: center'],

                                        //         ],
                                    ],
                                ]); ?>
                          </div>
                        </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                  </div>
                </div>
              </div>
            <?php endif;?>
            </div>
</div>



 


    <?php ActiveForm::end(); ?>

</div>
