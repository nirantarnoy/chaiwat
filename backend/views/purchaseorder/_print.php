<?php 
 use \backend\models\Product;
 use \backend\models\Vendor;
use yii\web\UrlManager;
use yii\helpers\BaseUrl;

 $img_url = Yii::$app->UrlManager->getBaseUrl(true).'/uploads/logo/chaiwat.jpg';
 //echo $img_url;
?>
<table width="100%">
	<tr>
		<td class="po-logo">
			<img src="<?=$img_url?>" style="width: 40%;"/>
		</td>
		<td class="po-logo">
			<h3>บริษัท เชียงใหม่ชัยวัฒน์(1991)จำกัด</h3> <br />
		24/1-3 หมู่5 ถนนโชตนา ตำบลเวียง อำเภอฝาง จังหวัดเชียงใหม่ 50115 โทร 053-451-242
		</td>
	</tr>
</table>

<table width="100%">
		
	<tr>
		<td style="width: 100%;text-align: center;font-size: 16px;padding: 15px;"  ><b>ใบสั่งซื้อ</b></td>			
	</tr>
</table>
<table class="po-vendorx" width="100%">
	<tr>
		<td width="60%">
			<table class="po-vendor" width="100%">
				<tr>
					<td width="20%" style="font-size: 14px;">ผู้ขาย <?php echo Vendor::getVendorname($model->vendor_id)?></td>
					
				</tr>
				<tr>
					<td width="100%" style="font-size: 14px;">ที่อยู่</td>
				</tr>
		    </table> 
		</td>
		<td width="40%">
			<table class="po-vendor" width="100%">
				<tr>
					<td style="width: 40%;font-size: 14px;">เลขที่</td>
				     <td style="width: 60%;"><b><?=$model->purchase_order?></b></td>
				</tr>
				<tr>
					<td style="width: 40%;font-size: 14px;">วันที่</td>
				    <td style="width: 60%;"><b><?=date('d-m-Y',$model->purchase_date)?></b></td>
				</tr>
			</table>
		</td>		
	</tr>
		
	<tr>
		<td colspan="7" style="font-size: 14px;text-align: center;height: 20px;"></td>
	</tr>
</table>

<table width="100%" class="po-detail">
	<tr bgcolor="#ccc">
		<td border="1"  style="font-size: 14px;text-align: center;padding: 15px;">ลำดับ</td>
		<td  style="font-size: 14px;">รหัสสินค้า</td>
		<td colspan="2"  style="font-size: 14px;">รายละเอียด</td>
		<td  style="font-size: 14px;text-align: right;">จำนวน</td>
		<td width="10%"  style="font-size: 14px;text-align: right;">ราคา</td>
		<td width="10%"  style="font-size: 14px;text-align: right;">รวม</td>
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

<table class="po-bottom" width="100%">
	<tr>
		<td colspan="7" style="font-size: 14px;text-align: center;height: 20px;"></td>
	</tr>
	<tr>
		<td width="50%">
			<table class="po-vendor" width="100%">
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">................................................</td>				
				</tr>
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">วันที่..........................................</td>				
				</tr>
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">ผู้สั่งซื้อ</td>
				</tr>
		    </table> 
		</td>
		<td width="50%">
			<table class="po-vendor" width="100%">
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">................................................</td>				
				</tr>
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">วันที่..........................................</td>				
				</tr>
				<tr>
					<td width="100%" style="font-size: 14px;text-align: center;">ผู้อนุมัติ</td>
				</tr>
		    </table> 
		</td>
	</tr>
</table>

