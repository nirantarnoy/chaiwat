<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use toxor88\switchery\Switchery;
/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form">

    <?php $form = ActiveForm::begin(['options'=>['encrypt'=>'multipart/form-data']]); ?>

     <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-6">
                
                  <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                  <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                  <?php echo $form->field($model, 'photo')->fileInput(['maxlength' => true]) ?>
                   <input type="hidden" name="old_photo" value="<?=$model->photo?>" />
                  <?php echo $form->field($model, 'status')->widget(Switchery::className(),['options'=>['label'=>'']]) ?>

                  <div class="form-group">
                      <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                  </div>
                </div>
                <div class="col-lg-6">
                   
                </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                           if(!$model->isNewRecord){
                             echo Html::img('@web/uploads/images/'.$model->photo,['style'=>'width: 50%;']);
                           }
                         ?>
                    </div>
                </div>
                </div>
              </div>
            </div>
          </div>

    <?php ActiveForm::end(); ?>

</div>
