<?php
error_reporting(0);
 ?>

<div class="row">
	<div class="col-lg-12">
		<table class="table table_bordered table-striped" style="width: 100%">
			<thead>
				<tr>
					<th style="text-align: left">ลำดับ</th>
					<th style="text-align: left">รหัสสินค้า</th>
					<th style="text-align: left">ชื่อ</th>
					<th style="text-align: left">หน่วย</th>
					<th style="text-align: left">กลุ่ม</th>
					<th style="text-align: left">ประเภท</th>
					<th style="text-align: left">ลักษณะ</th>
					<th style="text-align: left">ยี่ห้อ</th>
					<th style="text-align: left;width: 20px;">ราคาขาย</th>
					<th style="text-align: left;width: 20px;">สั่งซื้อ</th>
					<th style="text-align: right;width: 20px;">ขาย</th>
					<th style="text-align: right;width: 20px;">ซื้อ</th>
					<th style="text-align: right;width: 20px;">คงเหลือ</th>
					<th style="text-align: right">คืน</th>
					<th style="text-align: right">ปรับปรุง</th>
					<th style="text-align: right">ทุนรวม</th>
					<th style="text-align: right">ต้นทุน</th>
					<th style="text-align: right;width: 15px;">ผู้จำหน่าย</th>

				</tr>
			</thead>
			<tbody>
				<?php if(count($list)>0):?>
				<?php $i=0;?>
				<?php foreach($list as $value):?>
				<?php $i+=1;?>
				<tr>
					 <?php
					 	$po_qty = $value->purch_qty == 0?'-':number_format($value->purch_qty,0);
					 	$so_qty = $value->sale_qty == 0?'-':number_format($value->sale_qty,0);
					 	$qty = $value->qty == 0?'-':number_format($value->qty,0);
					 	$cost = $value->cost == 0?'-':number_format($value->cost,0);
					 	$cost_sum = $value->cost_sum == 0?'-':number_format($value->cost_sum,0);
					 	$sale_price = $value->sale_price == 0?'-':number_format($value->sale_price,0);
					 	$return_qty = $value->return_qty == 0?'-':number_format($value->return_qty,0);
					 	$adjust_qty = $value->adjust_qty == 0?'-':number_format($value->adjust_qty,0);
					 ?>
					<td><?=$i?></td>
					<td><?=$value->product_code?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?=$value->name?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?php echo \backend\models\Unit::getUnitname($value->unit_id)?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?= \backend\models\Category::getCategorycode($value->category_id)?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?= \backend\models\Producttype::getTypename($value->type_id)?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?= \backend\models\Property::getPropertyname($value->property_id)?></td>
					<td><?= \backend\models\Brand::getBrandname($value->brand_id)?></td>
					<td style="text-align: right"><?=$sale_price?></td>
					<td style="text-align: right"><?= $value->mode == 1?'Yes':'No'?></td>
					<td style="text-align: right"><?=$so_qty?></td>
					<td style="text-align: right"><?=$po_qty?></td>
					<td style="text-align: right"><?=$qty?></td>
					<td style="text-align: right"><?=$return_qty?></td>
					<td style="text-align: right"><?=$adjust_qty?></td>
					<td style="text-align: right"><?=$cost_sum?></td>
					<td style="text-align: right"><?=$cost?></td>
					<td style="overflow: hidden; text-overflow: ellipsis;white-space: nowrap;"><?= \backend\models\Vendor::getVendorname($value->vendor_id)?></td>

				</tr>
			<?php endforeach;?>
		<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
