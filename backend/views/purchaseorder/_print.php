<?php 
 use \backend\models\Product;
 use \backend\models\Vendor;
use yii\web\UrlManager;
use yii\helpers\BaseUrl;

 $img_url = Yii::$app->UrlManager->getBaseUrl(true).'/uploads/logo/chaiwat.jpg';
 //echo $img_url;
?>
<div class="row">
	<div class="col-lg-12">
		<img src="<?=$img_url?>" style="width: 20%;"/>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<table width="100%">
		
			<tr>
				<td style="width: 30%;text-align: center;font-size: 16px;padding: 15px;" colspan="7" ><b>ใบสั่งซื้อ</b></td>
			</tr>
			<tr>
				<td style="width: 10%;font-size: 14px;">ผู้ขาย</td>
				<td style="width: 60%;font-size: 14px;" colspan="4"><?=Vendor::getVendorname($model->vendor_id)?></td>
				<td style="width: 10%;font-size: 14px;">เลขที่</td>
				<td style="width: 20%"><b><?=$model->purchase_order?></b></td>
			</tr>
			<tr>
				<td style="width: 10%;font-size: 14px;">ที่อยู่</td>
				<td style="width: 20%"></td>
				<td style="width: 30%" colspan="2"></td>
				<td style="width: 10%"></td>
				<td style="width: 10%;font-size: 14px;">วันที่</td>
				<td style="width: 20%"><b><?=date('d-m-Y',$model->purchase_date)?></b></td>
			</tr>
			<tr>
				<td colspan="7" style="font-size: 14px;text-align: center;height: 20px;"></td>
			</tr>
			<tr bgcolor="#ccc">
				<td border="1"  style="font-size: 14px;text-align: center;padding: 15px;">ลำดับ</td>
				<td  style="font-size: 14px;">รหัสสินค้า</td>
				<td colspan="2"  style="font-size: 14px;">รายละเอียด</td>
				<td  style="font-size: 14px;text-align: right;">จำนวน</td>
				<td  style="font-size: 14px;text-align: right;">ราคา</td>
				<td  style="font-size: 14px;text-align: right;">รวม</td>
			</tr>
			<?php $i=0;?>
			<?php $total=0;?>
			<?php foreach($modelline as $data):?>
			<?php $i+=1;?>
			<?php $total = $total + $data->line_amount;?>
			<tr>
				<td border="1"  style="font-size: 14px;text-align: center;padding: 15px;"><?=$i?></td>
				<td  style="font-size: 14px;"><?=Product::getProdcode($data->product_id)?></td>
				<td colspan="2"  style="font-size: 14px;"><?=Product::getProdname($data->product_id)?></td>
				<td  style="font-size: 14px;text-align: right;"><?=$data->qty?></td>
				<td  style="font-size: 14px;text-align: right;"><?=$data->price?></td>
				<td  style="font-size: 14px;text-align: right;"><?=$data->line_amount?></td>
			</tr>
			<?php endforeach;?>
			<tr bgcolor="#ccc">
				<td colspan="6" style="font-size: 14px;text-align: right;padding: 15px;">จำนวนเงินรวม</td>
				<td  style="font-size: 14px;text-align: right;"><?=$total?></td>
			</tr>
		</table>
	</div>
</div>