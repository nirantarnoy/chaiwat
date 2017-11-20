<?php
use kartik\select2\Select2;
use kartik\file\FileInput;

$this->title = "นำเข้าข้อมูล";
 ?>
<div class="row">
	<div class="col-lg-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<p><b><i class='fa fa-upload'></i> นำเข้ารหัสสินค้า</b></p>
			</div>
			<div class="box-body">
				<form>
				<p>นำเข้ารหัสสินค้าจากไฟล์ Excel</p>
				<div class="row">
					<div class="col-lg-6">
						<?php
						echo FileInput::widget([
                                     'model' => $upload_product,
                                     'attribute' => 'file_product[]',
                                     'id'=>'product_file',
                                     'options' => ['multiple' => true,'accept' => ['.TXT','.PDF','.PNG','.JPG','.GIF'],'style'=>'width: 300px'],
                                     'pluginOptions' => [
                                     'showUpload'=>false,
                                     'maxFileCount'=>3,
                                     ],
                                 ]);
						?>
					</div>
					<div class="col-lg-6">
						<input type="submit" value=" นำเข้า" class="btn btn-primary">
					</div>
				</div>	
				</form>
			</div>
		</div>
			
		
		
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<p><b><i class='fa fa-upload'></i> นำเข้ารหัสผู้ขาย/เจ้าหนี้</b></p>
			</div>
			<div class="box-body">
				<form>
				<p>นำเข้ารหัสสินค้าจากไฟล์ Excel</p>
				<div class="row">
					<div class="col-lg-6">
						<?php
						echo FileInput::widget([
                                     'model' => $upload_vendor,
                                     'attribute' => 'file_vendor[]',
                                     'id'=>'vendor',
                                     'options' => ['multiple' => true,'accept' => ['.TXT','.PDF','.PNG','.JPG','.GIF'],'style'=>'width: 300px'],
                                     'pluginOptions' => [
                                     'showUpload'=>false,
                                     'maxFileCount'=>3,
                                     ],
                                 ]);
						?>
					</div>
					<div class="col-lg-6">
						<input type="submit" value=" นำเข้า" class="btn btn-primary">
					</div>
				</div>	
				</form>
			</div>
		</div>
			
		
		
	</div>
</div>