<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Purchaseorder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchaseorder-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>
     <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'purchase_order')->textInput(['maxlength' => true,'readonly'=>'readonly','value'=>$model->isNewRecord?$runno:$model->purchase_order]) ?>
                </div>
                  <div class="col-lg-3">
                     <?php if($model->isNewRecord){$model->purchase_date = date('d-m-Y');}else{$model->purchase_date = date('d-m-Y',$model->purchase_date);} ?>
                     <?= $form->field($model, 'purchase_date')->widget(DatePicker::className(), [ 'pluginOptions' => [
                                          'format' => 'dd-mm-yyyy',
                                          //'value' => date('d-m-Y'),
                                          'autoclose' => true,
                                          'todayHighlight' => true
                                      ], 'options' => ['style' => 'width: 100%',
                                           
                                      ]])->label() ?>
                </div>
                  <div class="col-lg-3">
                    <?= $form->field($model, 'vendor_id')->widget(Select2::className(),[
                            'data'=> ArrayHelper::map(\backend\models\Vendor::find()->all(),'id','name'),
                            'options' => ['placeholder'=>'เลือกผู้ขาย'],

                    ]) ?>
                </div>
                  <div class="col-lg-3">
                    <?= $form->field($model, 'purchase_amount')->textInput(['readonly'=>'readonly']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'status')->textInput(['readonly'=>'readonly']) ?>
                </div>
                  <div class="col-lg-3">
                     <?php if($model->isNewRecord){$model->created_at = date('d-m-Y');}else{$model->created_at = date('d-m-Y',$model->created_at);} ?>
                    <?= $form->field($model, 'created_at')->textInput(['readonly'=>'readonly']) ?>
                </div>
                  <div class="col-lg-3">
                    <?= $form->field($model, 'note')->textarea(['maxlength' => true]) ?>
                </div>
                  <div class="col-lg-3">
                    
                </div>
            </div>
            <hr />
               <div class="row">
                <div class="col-lg-3">
                  <?php
                    echo Typeahead::widget([
                          'name' => 'country',
                          'options' => ['placeholder' => 'ค้นหารหัสสินค้า...'],
                          'pluginOptions' => ['highlight'=>true],
                          'dataset' => [
                              [
                                  'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                  'display' => 'value',
                                  'limit' => '100',
                                  'templates'=>[
                                    'notFound' => '<div class="text-danger" style="padding:0 8px">ไม่พบสินค้า</div>',
                                    'suggestion' => new \yii\web\JsExpression("Handlebars.compile('<div><span class=\'fa fa-picture-o\' style=\'font-size:1.5em;\'></span> {{product_code}} {{name}}</div>')"),
                                  ],
                                  //'prefetch' => '/samples/countries.json',
                                  'remote' => [
                                      'url' => 'index.php?r=sale%2Fproductlist'.'&q=%QUERY',
                                      'wildcard' => '%QUERY'
                                  ]
                              ]
                              ],
                              'pluginEvents'=>[
                                "typeahead:select" => "
                                  function(e,s){
                                    if($(document).find('.saleline-id-'+s.id).length >=1){

                                    }else{
                                      $.ajax({
                                        type: 'POST',
                                        url: '".Url::to(['/purchaseorder/addline'])."',
                                        data: {data:s},
                                        success: function(data){
                                          $('.add-saleline').parent().append(data);
                                          var cnt =0;
                                          $('#lineitem >tbody >tr').each(function(){
                                            cnt+=1;
                                            $(this).find('td:first-child').text(cnt);
                                          });
                                            sumall();
                                        }
                                      });
                                    }
                                  }
                                "
                              ]
                          
                      ]);
                  ?>
                </div>
               </div>
                <table class="table" id="lineitem">
                <thead>
                  <tr>
                    <th>#</th>
                     <th>รหัสสินค้า</th>
                     <th>ชื่อสินค้า</th>
             <th>จำนวน</th>
            
                     <th>ราคา</th>
                     <th>รวมเงิน</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="add-saleline">
                  <?php if(!$model->isNewRecord):?>
                    <?php if(count($modelline)>0):?>
                      <?php foreach($modelline as $value):?>
                        <tr class="saleline-id-">
                          <td>1</td>
                          <td>
                            <input type="text" class="form-control product_code" name="product_code[]" value="<?=\backend\models\Product::getProdcode($value->product_id)?>" disabled="disabled" /> 
                            <input type="hidden" class="form-control product_id" name="product_id[]" value="<?=$value->product_id?>" /> 
                          </td>
                          <td>
                            <input type="text" class="form-control name" name="name[]" value="<?=\backend\models\Product::getProdname($value->product_id)?>" disabled="disabled" /> 
                          </td>
                          <td>
                            <input type="text" class="form-control qty" name="qty[]" value="<?=$value->qty?>" onkeydown="eventNumber($(this));" onchange="linecal($(this));" /> 
                          </td>
                          <td>
                            <input type="text" class="form-control price" name="price[]" value="<?=$value->price?>" onchange="linecal($(this));" />
                          </td>
                          <td><input type="text" class="form-control line_amount" name="line_amount[]" value="<?=$value->line_amount?>" /></td>
                          <td><div class="btn btn-warning line_remove" onclick="removeline($(this));"><i class="fa fa-minus"></i></div></td>
                        </tr>
                      <?php endforeach;?>
                    <?php endif;?>
                   <?php endif;?>
                </tbody>
                <tfoot>
                  <tr>
                    <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td style="text-align: right;"><h4>รวม</h4></td>
                     <td>
                        <input type="text" value="" class="form-control total_all" readonly /> 
                     </td>
                  </tr>
                </tfoot>
               </table>
        </div>
    </div>
    
   
    <?php ActiveForm::end(); ?>

    

</div>
<?php $this->registerJs('
  $(function(){
   sumall();
  });
  function sumall(){
    var amount = 0;
    $(".add-saleline >tr").each(function(){
      amount = parseFloat(amount) + parseFloat($(this).closest("tr").find(".line_amount").val());
    });
    $(".total_all").val(amount);
  }
  function eventNumber(e){
    // var x = e.val().replace(/[^0-9\.]/g,"");
    //   e.val(x);

      if(e.keyCode == 46 || e.keyCode == 8){

      }else{
        if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)){
          e.preventDefault();
        }
      }
  }
  function linecal(e){
    var qty = e.closest("tr").find(".qty").val();
    var price = e.closest("tr").find(".price").val();
    e.closest("tr").find(".line_amount").val(parseFloat(qty * price));
    sumall();
  }
  function removeline(e){
    if(confirm("ต้องการลบรายการนี้ใช่หรือไม่")){
      e.parent().parent().remove();
    }
    
  }

  ',static::POS_END)?>