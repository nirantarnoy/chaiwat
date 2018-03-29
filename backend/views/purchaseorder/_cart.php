<?php
use yii\web\Session;
use yii\helpers\Url;


$session = Yii::$app->session;

$this->title = "รายการสั่งซื้อ";

//print_r($session['cart']);
 ?>
<div class="row">
	<div class="col-lg-12">
		<?php if(!isset($session['cart'])):?>
			<div style="width: 100%;text-align: center;"><b><p>ไม่พบรายการ</p></b></div>
		<?php endif;?>
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
				<form id="form-cart" action="index.php?r=purchaseorder/submitcart" method="get">
				<?php 
                  if(isset($session['cart'])):
                  	foreach($session['cart'] as $key =>$value):
				?>
						<tr>
							<td style="vertical-align: middle;"><i class="fa fa-trash-o text-red remove-cart" style="cursor: pointer;"></i></td>
							<td style="vertical-align: middle;"><?=$value['product_id']?>
								<input type="hidden" class="prodrec_id" name="product_id" value="<?=$key?>">
								<input type="hidden" class="product_id" name="product_id" value="<?=$value['product_id']?>">
							</td>
							<td style="vertical-align: middle;"><?=$value['name']?></td>
							<td>
								<input type="text" class="form-control price" style="text-align: right;" name="price" value="<?=$value['price']?>" onchange="calprice($(this));">
							</td>
							<td>
								<input type="number" class="form-control qty" style="text-align: right;" name="qty" value="<?=$value['qty']?>" onchange="calqty($(this));">
							</td>
							<td style="text-align: right;vertical-align: middle;"><p class="line_total"><?=number_format($value['price'] * $value['qty'])?></p></td>
						</tr>
				<?php 
				 endforeach;
				 ?>
				  
				 <?
				 endif;
				 ?>
				 </form>
			</tbody>
			<tfoot>
				<?php //if(isset($session['cart'])):?>
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
						<div class="btn btn-danger remove-order"><i class="fa fa-trash"></i> ล้างรายการ </div>
						<div class="btn btn-default back-product"><i class="fa fa-plus"></i> เพิ่มสินค้า </div>
						<div class="btn btn-primary btn-submit-cart"><i class="fa fa-save"></i> บันทึกใบสั่งซื้อ </div>
					</td>
				</tr>
			<?php //endif;?>
			</tfoot>
		</table>
	<?php endif;?>
	</div>
</div>
<?php 
  $url_to_updatecart =  Url::to(['purchaseorder/updatecart'],true);
  $url_to_remove_itemcart =  Url::to(['purchaseorder/removeitemcart'],true);
  $url_to_submitcart =  Url::to(['purchaseorder/submitcart'],true);
  $this->registerJs('

  	    $(function(){
  	      sumall();
  	    });
		$(".table-cart >tbody >tr td:last-child").each(function(){
			var total = 0;
			total = total + parseFloat($(this).text());
			$(".table-cart >tfoot").find(".total").text(total);
		});
		$(".remove-cart").click(function(){
			if(confirm("คุณต้องการลบรายการนี้ใช่หรือไม่")){
				var prodids = $(this).closest("tr").find(".prodrec_id").val();
				$(this).parent().parent().remove();
				$.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_remove_itemcart.'",
                  data: {prodid: prodids},
                  success: function(data){
                   // alert(data);
					$(".cnt-pick").text(data);
                  }
        	    });
				sumall();
			}

		});
		$(".remove-order").click(function(){
            $.get("index.php?r=purchaseorder/removeorder");
            location.reload();
		});
		$(".back-product").click(function(){
		      $("#myModal_cart").modal("hide");
   		});
   		$(".btn-submit-cart").click(function(){
			$.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_submitcart.'",
                  data: {id: 0},
                  success: function(data){
                    alert(data);
                  }
        	});
   		});
		function calprice(e){
			var prodids = e.closest("tr").find(".prodrec_id").val();
			var qtys = e.closest("tr").find(".qty").val();
			var prices = e.val();
			e.closest("tr").find(".line_total").text(parseFloat(qtys*prices));

			$.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_updatecart.'",
                  data: {prodid: prodids,prc:prices,qty: qtys},
                  success: function(data){
                   // alert(data);
                  }
        	});

			sumall();
		}
		function calqty(e){
			var prodids = e.closest("tr").find(".prodrec_id").val();
			var qtys = e.val();
			var prices = e.closest("tr").find(".price").val();
			e.closest("tr").find(".line_total").text(parseFloat(qtys*prices));
			
			$.ajax({
                  type: "post",
                  dataType: "html",
                  url: "'.$url_to_updatecart.'",
                  data: {prodid: prodids,prc:prices,qty: qtys},
                  success: function(data){
                    //alert(data);
                  }
        	});
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