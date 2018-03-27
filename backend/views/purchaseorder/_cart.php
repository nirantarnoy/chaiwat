<?php
use yii\web\Session;

$session = Yii::$app->session;

$this->title = "รายการสั่งซื้อ";

//print_r($session['cart']);
 ?>
<div class="row">
	<div class="col-lg-12">
		
		<table class="table table-cart">
			<thead>
				<tr>
					<th width="5%"></th>
					<th width="20%">รหัสสินค้า</th>
					<th width="45%">รายละเอียด</th>
					<th width="10%" style="text-align: right;">ราคา</th>
					<th width="10%" style="text-align: right;">จำนวน</th>
					<th width="20%" style="text-align: right;">รวม</th>
				</tr>
			</thead>
			<tbody>
				<?php 
                  if(isset($session['cart'])):
                  	foreach($session['cart'] as $key =>$value):
				?>
				<tr>
					<td style="vertical-align: middle;"><i class="fa fa-trash-o text-red remove-cart" style="cursor: pointer;"></i></td>
					<td style="vertical-align: middle;"><?=$value['product_id']?></td>
					<td style="vertical-align: middle;"><?=$value['name']?></td>
					<td>
						<input type="text" class="form-control price" style="text-align: right;" name="price" value="<?=$value['price']?>" onchange="calprice($(this));">
					</td>
					<td>
						<input type="number" class="form-control qty" style="text-align: right;" name="qty" value="<?=$value['qty']?>" onchange="calqty($(this));">
					</td>
					<td style="text-align: right;vertical-align: middle;"><p class="line_total"><?=$value['price'] * $value['price']?></p></td>
				</tr>
				<?php 
				 endforeach;
				 ?>
				<?php else: ?>
				  <div style="width: 100%;text-align: center;"><b><p>ไม่พบรายการ</p></b></div>
				 <?
				 endif;
				 ?>
			</tbody>
			<tfoot>
				<?php if(isset($session['cart'])):?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align: right">ยอดรวม</td>
					<td style="text-align: right;font-size: 16px"><b><p class="total"></p></b></td>
				</tr>
				
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<div class="btn btn-danger remove-order"><i class="fa fa-trash"></i> ทิ้งรายการ </div>
						<div class="btn btn-default"><i class="fa fa-plus"></i> เพิ่มสินค้า </div>
						<div class="btn btn-primary"><i class="fa fa-save"></i> บันทึกใบสั่งซื้อ </div>
					</td>
				</tr>
			<?php endif;?>
			</tfoot>
		</table>
	<?php endif;?>
	</div>
</div>
<?php 
  $this->registerJs('
		$(".table-cart >tbody >tr td:last-child").each(function(){
			var total = 0;
			total = total + parseFloat($(this).text());
			$(".table-cart >tfoot").find(".total").text(total);
		});
		$(".remove-cart").click(function(){
			if(confirm("คุณต้องการลบรายการนี้ใช่หรือไม่")){
				$(this).parent().parent().remove();
				sumall();
			}

		});
		$(".remove-order").click(function(){
            $.get("index.php?r=purchaseorder/removeorder");
		});
		function calprice(e){
			var qty = e.closest("tr").find(".qty").val();
			var price = e.val();
			e.closest("tr").find(".line_total").text(parseFloat(qty*price));

			sumall();
		}
		function calqty(e){
			var qty = e.val();
			var price = e.closest("tr").find(".price").val();
			e.closest("tr").find(".line_total").text(parseFloat(qty*price));

			sumall();
		}
		function sumall(){
			var amt = 0;
           $(".table-cart >tbody >tr td:last-child").each(function(){
			 amt = amt + parseFloat($(this).find(".line_total").text());
			}); 
			$(".table-cart >tfoot").find(".total").text(parseFloat(amt).toLocaleString());
		}

  ');
?>