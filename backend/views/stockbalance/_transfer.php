<?php
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;


$this->title = "โอนสินค้า";
?>
<?php $form = ActiveForm::begin(['id'=>'myform','action'=>['stockbalance/transfersubmit']])?>
<div class="row">
	<div class="col-lg-12">
		<input type="submit" class="btn btn-success" value="ตกลง"/>
	</div>
</div><br />
<div class="row">
	<div class="col-lg-12">
		<div class="panel">
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th>รหัสสินค้า</th>
							<th>ชื่อสินค้า</th>
							<th>จำนวน</th>
							<th>จากคลัง</th>
							<th>ไปคลัง</th>
						</tr>
					</thead>
					<tbody>
					<?php if(count($model)>0):?>
						<?php foreach($model as $key =>$data):?>
							<tr id="<?$data->id?>">
								<td><?=$key+1?></td>
								<td>
									<input type="hidden" name="product_id[]" value="<?=$data->product_id?>" />
									<?=\backend\models\Product::getProdCode($data->product_id)?>
								</td>
								<td><?=\backend\models\Product::getProdName($data->product_id)?></td>
								<td><input type="number" class="form-control" name="qty[]" value="<?=$data->qty?>" /></td>
								<td>
									<?=\backend\models\Warehouse::findName($data->warehouse_id)?>
									<input type="hidden" class="warehouse_id" name="warehouse_id[]" value="<?=$data->warehouse_id?>" />
								</td>
								<td>
									<?php
										echo Select2::widget([
												'name'=>'towarehouse[]',
												'model'=>$modelWarehouse,
												'attribute'=>'name',
												'data'=>ArrayHelper::map(\backend\models\Warehouse::find()->where(['!=','id',$data->warehouse_id])->all(),'id','name'),
												'options'=>['placeholder'=>'เลือกคลังปลายทาง',
													'onchange'=>'
														
													'
												],
												'pluginOptions'=>[
													'allowClear'=> true,
												]
											]);
									?>
								</td>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end();?>