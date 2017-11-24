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
$prodtype = \backend\models\Producttype::find()->where(['status'=>1])->all();
$prodprop = \backend\models\Property::find()->where(['status'=>1])->all();
$sub_cat = \backend\models\Subcategory::find()->where(['status'=>1])->all();
$unit = \backend\models\Unit::find()->where(['status'=>1])->all();
$brand = \backend\models\Brand::find()->where(['status'=>1])->all();
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>


<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">ข้อมูลสินค้า</a></li>
              <?php if(!$model->isNewRecord):?>
            <!--   <li><a href="#tab_2" data-toggle="tab">สินค้าคงคลังและความเคลื่อนไหว</a></li> -->
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
                                                          // $.post("index.php?r=product/showsubcategory&id=' . '"+$(this).val(),function(data){
                                                          // $("select#sub_cat").html(data);
                                                          // $("select#sub_cat").prop("disabled","");

                                                          });
                                                      ',
                                                    ],
                                                    ]

                                                  )->label() ?>
                                  
                                     <?= $form->field($model, 'type_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($prodtype,'id','name'),
                                                    'options'=>['placeholder' => 'เลือกประเภทสินค้า','class'=>'form-control','id'=>'prodtype',
                                                       'onchange'=>'',
                                                    ],
                                                    ]

                                                  )->label() ?>
                                      <?= $form->field($model, 'property_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($prodprop,'id','name'),
                                                    'options'=>['placeholder' => 'เลือกคุณสมบัติสินค้า','class'=>'form-control','id'=>'prodprop',
                                                       'onchange'=>' 
                                                        
                                                      ',
                                                    ],
                                                    ]

                                                  )->label() ?>
                                                   <?= $form->field($model, 'brand_id')->widget(Select2::className(),
                                                    [
                                                     'data'=> ArrayHelper::map($brand,'id','name'),
                                                    'options'=>['placeholder' => 'เลือกยี่ห้อ','class'=>'form-control','id'=>'brand',
                                                       'onchange'=>' 
                                                        
                                                      ',
                                                    ],
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

                                  
                                </div>
                                <div class="col-lg-6">
                                   <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'qty')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'min_qty')->textInput(['maxlength' => true]) ?>

                                    <?= $form->field($model, 'max_qty')->textInput(['maxlength' => true]) ?>
                                </div>
                                </div>

                                    <hr />
                                    

                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-1">
                              <div class="form-group">
                                      <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                                  </div>
                            </div>
                          </div>
              </div>
    
            </div>
</div>



 


    <?php ActiveForm::end(); ?>

</div>
