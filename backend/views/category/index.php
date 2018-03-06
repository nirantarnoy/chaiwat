<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use backend\assets\ICheckAsset;
ICheckAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หมวดผลิตภัณฑ์';
$this->params['breadcrumbs'][] = $this->title;


$this->registerJsFile(
    '@web/js/stockbalancejs.js?V=001',
    ['depends' => [\yii\web\JqueryAsset::className()]],
    static::POS_END
);

?>
<div class="category-index">
<input type="hidden" name="listid" class="listid" value="">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
           <div>
        <?= Html::a('<i class="fa fa-plus-circle"></i> สร้างประเภทผลิตภัณฑ์', ['create'], ['class' => 'btn btn-success']) ?>
        <div class="btn btn-danger btn-bulk-remove" disabled><i class="fa fa-trash"></i> ลบ <span class="remove_item">[0]</span></div>
         <div class="btn-group pull-right" style="bottom: 10px">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
      </div>
      </div>
      </div>
      <div class="panel-body">
<div class="table-grid">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],

            //'id',
            'name',
            'description',
            //'status',
            [
               'attribute'=>'status',
               'format' => 'html',
               'value'=>function($data){
                 return $data->status === 1 ? '<div class="label label-success">Active</div>':'<div class="label label-default">Inactive</div>';
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
                                        <li><a href="'.Url::toRoute(['/category/view', 'id'=>$model->id]).'">'.'View'.'</a></li>
                                        <li><a href="'.Url::toRoute(['/category/update', 'id'=>$model->id]).'">'.'Update'.'</a></li>
                                        <li><a onclick="return confirm(\'Confirm ?\')" href="'.Url::to(['/category/delete', 'id'=>$model->id],true).'">Delete</a></li>
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
<?php
$url_to_delete = Url::to(['category/bulkdelete'],true);
  $this->registerJs('
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
    ');
 ?>
