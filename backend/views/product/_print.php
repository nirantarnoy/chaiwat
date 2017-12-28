<?php
 ?>

<div class="row">
	<div class="col-lg-12">
		<table class="table table_bordered table-striped" style="width: 100%">
			<thead>
				<tr>
					<th style="text-align: left">ลำดับ</th>
					<th style="text-align: left">รหัสสินค้า</th>
					<th style="text-align: left">ชื่อ</th>
					<th style="text-align: left">กลุ่ม</th>
					<th style="text-align: left">ประเภท</th>
					<th style="text-align: left">คุณสมบัติ</th>
					<th style="text-align: left">สั่งซื้อ</th>
					<th style="text-align: right">ซื้อ</th>
					<th style="text-align: right">ขาย</th>
					<th style="text-align: right">จำนวน</th>
					<th style="text-align: left">หน่วย</th>
					<th style="text-align: right">ต้นทุน</th>
					<th style="text-align: right">ราคาขาย</th>
				</tr>
			</thead>
			<tbody>
				<?php if(count($list)>0):?>
				<?php $i=0;?>
				<?php foreach($list as $value):?>
				<?php $i+=1;?>
				<tr>
					<td><?=$i?></td>
					<td><?=$value->product_code?></td>
					<td><?=$value->name?></td>
					<td><?= \backend\models\Category::getCategorycode($value->category_id)?></td>
					<td><?= \backend\models\Producttype::getTypename($value->type_id)?></td>
					<td><?= \backend\models\Property::getPropertyname($value->property_id)?></td>
					<td style="text-align: right"><?= $value->mode == 1?'Yes':'No'?></td>
					<td style="text-align: right"><?=number_format($value->sale_qty,0)?></td>
					<td style="text-align: right"><?=number_format($value->purch_qty,0)?></td>
					<td style="text-align: right"><?=number_format($value->qty,0)?></td>
					<td><?= \backend\models\Unit::getUnitname($value->unit_id)?></td>
					<td style="text-align: right"><?=number_format($value->cost,0)?></td>
					<td style="text-align: right"><?=number_format($value->sale_price,0)?></td>
				</tr>
			<?php endforeach;?>
		<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
