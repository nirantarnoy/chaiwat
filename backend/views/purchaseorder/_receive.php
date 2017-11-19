<?php 
 if(count($model)>0):
?>
 <?php 
   foreach($model as $value):
 ?>
  <tr>
  	<td><?=\backend\models\Product::getProdCode($value->product_id)?></td>
  	<td><?=\backend\models\Product::getProdName($value->product_id)?></td>
  	<td>
  		<input type="text" class="form-control qty" name="qty[]" value="<?=$value->qty?>"> 
 		<input type="hidden" class="rec_qty" name="rec_qty[]" value="<?=$value->qty?>"> 
  		<input type="hidden" class="rec_qty" name="product_id[]" value="<?=$value->product_id?>"> 
  	    <input type="hidden" class="rec_qty" name="poid[]" value="<?=$value->purchase_order_id?>"> 
  	</td>
  </tr>
<?php 
  endforeach;
 ?>
<?php 
 endif;
?>