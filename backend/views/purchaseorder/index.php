<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PurchaseorderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ใบสั่งซื้อ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchaseorder-index">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
           <div>
            <?= Html::a('<i class="fa fa-plus-circle"></i> สร้างใบสั่งซื้อ', ['create'], ['class' => 'btn btn-success']) ?>
            <div class="btn-group pull-right" style="bottom: 10px">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
      </div>
      </div>
      <div class="panel-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'purchase_order',
            [
              'attribute'=>'vendor_id',
              'value' => function($data){
                return $data->vendor_id!=''?$data->vendorinfo->name:'';
              }
            ],
           // 'purchase_date',
            [
              'attribute'=>'purchase_date',
              'value' => function($data){
                return date('d-m-Y',$data->purchase_date);
              }
            ],
           // 'required_date',
            //'note',
            //'purchase_amount',
             [
              'attribute'=>'purchase_amount',
              'value' => function($data){
                return number_format($data->purchase_amount);
              }
            ],
            [
               'attribute'=>'status',
               'format' => 'raw',
               'value'=>function($data){
                 return $data->status === 1 ? '<div class="label label-success line-status" onclick="recline($(this));">Open</div>':'<div class="label label-default">Closed</div>';
               }
             ],
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
                                        <li><a href="'.Url::toRoute(['/purchaseorder/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
                                        <li><a href="'.Url::toRoute(['/purchaseorder/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
                                        <li><a onclick="return confirm(\'Confirm ?\')" href="'.Url::to(['/purchaseorder/delete', 'id'=>$model->id],true).'">Delete</a></li>
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
    <?php Pjax::end(); ?>
</div>


<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
              <h3><i class="fa fa-cubes"></i> รับสินค้า</h3>
          </div>
          <div class="modal-body">
             <?php $form = ActiveForm::begin(['id'=>'form-receive','action'=>Url::to(['purchaseorder/receivepurchase'],true)]); ?>
                  <div class="row">
                    <div class="col-lg-12">
                      <table class="table table-striped table-receive">
                        <thead>
                          <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                          </tr>
                        </thead>
                        <tbody class="receive-body">
                        </tbody>
                        <tfoot>
                          <tr>
                            <td>
                              <?php
                              echo '<label class="control-label">เลือกรับเข้าคลังสินค้า</label>';
                                echo Select2::widget([
                                      'name' => 'warehouseid',
                                      'value' => '',
                                      'data' => ArrayHelper::map(\backend\models\Warehouse::find()->all(),'id','name'),
                                      'options' => ['multiple' => false]
                                  ]);
                               ?>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
    <?php ActiveForm::end(); ?>
          </div>
          <div class="modal-footer">
            <button class="btn btn-save-receive" data-dismiss="modal">บันทึก</button>
          </div>
          </div>
        </div>
        </div>

<?php 
    $url_to_receive = Url::to(['purchaseorder/receivelist'],true);
    $url_to_recpo = Url::to(['purchaseorder/receivepurchase'],true);
    $this->registerJs('
    $(function(){
      // $(".line-status").on("click",function(){
      //   var poid = $(this).closest("tr").parent().parent().find("td:eq(1)").text();
      //   alert(poid);
      //   if(poid!=""){
      //     $.ajax({
      //       type: "post",
      //       dataType: "html",
      //       url: "'.$url_to_receive.'",
      //       data: {po: poid},
      //       success: function(data){
      //         //alert(data);return;
      //         $(".receive-body").empty();
      //         $(".receive-body").append(data);
      //         $("#myModal").modal("show");
      //       }
      //     });
      //   }
          
      // });

     $(".btn-save-receive").click(function(){
        $("form#form-receive").submit();
     });

    });

    function recline(e){
      var poid = e.closest("tr").find("td:eq(1)").text();
        //alert(poid);
        if(poid!=""){
          $.ajax({
            type: "post",
            dataType: "html",
            url: "'.$url_to_receive.'",
            data: {po: poid},
            success: function(data){
              //alert(data);return;
              $(".receive-body").empty();
              $(".receive-body").append(data);
              $("#myModal").modal("show");
            }
          });
        }
    }

  ',static::POS_END);?>