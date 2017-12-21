<?php

namespace backend\controllers;

use Yii;
use backend\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use backend\models\Modelfile;
use backend\models\Modelfile2;
use backend\models\Productimage;
use backend\models\StockbalanceSearch;
use backend\models\ViewStockSearch;
use yii\helpers\Json;
use kartik\mpdf\Pdf;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
   public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                    'index' => ['GET','POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $brand= '';
        $group= '';
        $product_type = '';
        $vendor = '';
        $property = '';
        $mode = '';
        $text_search = '';  

        $session = Yii::$app->session;

        if(Yii::$app->request->isPost){
            $group = Yii::$app->request->post('product_group');
            $product_type = Yii::$app->request->post('type');
            $brand = Yii::$app->request->post('brand');
            $vendor = Yii::$app->request->post('vendor');
            $property = Yii::$app->request->post('property');
            $mode = Yii::$app->request->post('mode');
            $text_search = Yii::$app->request->post('text_search');
           // echo $mode;return;
            //echo Yii::$app->request->post('new_brand')[0]; return;
            // print_r($product_type);
           // echo $product_type; 
           // print_r(Yii::$app->request->queryParams); return;   
          
            $session['group'] = $group;
            $session['product_type'] = $product_type;
            $session['property'] = $property;
            $session['brand'] = $brand;
            $session['vendor'] = $vendor;
            $session['mode'] = $mode;
            $session['text_search'] = $text_search;
        }

        
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['like','category_id',$session['group']])
                     ->andFilterWhere(['in','type_id',$session['product_type']])
                     ->andFilterWhere(['in','property_id',$session['property']])
                     ->andFilterWhere(['in','brand_id',$session['brand']])
                     ->andFilterWhere(['in','mode',$session['mode']])
                     ->andFilterWhere(['in','vendor_id',$session['vendor']])
                     ->andFilterWhere(['or',['like','product_code',$session['text_search']],['like','name',$session['text_search']]]);

        //$dataProvider->pagination->pageSize = 10;

        $modelfile = new Modelfile();
        $modelfile2 = new Modelfile2();

        if($modelfile->load(Yii::$app->request->post())){
           $uploaded = UploadedFile::getInstance($modelfile,"file");
           if(!empty($uploaded)){
            //echo $uploaded;return;
              $data = [];
              $data_save = 0;
              $data_fail = [];
              $data_all = 0;
              $upfiles = time() . "." . $uploaded->getExtension();
               if($uploaded->saveAs('../web/uploads/files/'.$upfiles)){
                 //echo "okk";return;
                  $myfile = '../web/uploads/files/'.$upfiles;
                $inputFileType = \PHPExcel_IOFactory::identify($myfile);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($myfile);

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                //for($row=1;$row <= $highestRow; $row++){
                 for($row=1;$row <= 300; $row++){
                  $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

                  if($row <=1){
                    continue;
                  }
                  if($rowData[0][0] == ''){
                   // $data_all +=1;
                    continue;
                  }
                          $modelprod = \backend\models\Product::find()->where(['product_code'=>$rowData[0][0]])->one();
                          if(count($modelprod)>0){
                            // $data_all +=1;
                            // array_push($data_fail,['name'=>$rowData[0][1]]);
                            continue;
                          }

                            $modelx = new \backend\models\Product();
                            $modelx->product_code = $rowData[0][0];
                            $modelx->name = $rowData[0][1];
                            $modelx->description = $rowData[0][1] ;
                        //    $modelx->category_id = $rowData[0][3];
                            $modelx->weight = 0;
                            $modelx->category_id = $this->checkCat($rowData[0][12]);
                            $modelx->unit_id = $this->checkUnit($rowData[0][2]);
                            $modelx->type_id = $this->checkType($rowData[0][13],$modelx->category_id);
                            $modelx->property_id = $this->checkProperty($rowData[0][14],$modelx->type_id);
                            $modelx->brand_id = $this->checkBrand($rowData[0][15]);
                            $modelx->price = 0;
                            $modelx->product_start = $rowData[0][4];
                            $modelx->sale_qty = $rowData[0][5];
                            $modelx->purch_qty = $rowData[0][6];
                            $modelx->return_qty = $rowData[0][7];
                            $modelx->adjust_qty = $rowData[0][8];
                            $modelx->cost_sum = $rowData[0][10];
                            $modelx->cost = $rowData[0][11];
                            $modelx->qty = $rowData[0][9];
                            $modelx->min_qty = 0;
                            $modelx->max_qty = 0;
                            $modelx->status = 1;
                            $modelx->group_id = $this->checkCat($rowData[0][12]);
                            $modelx->vendor_id = $this->checkVendor($rowData[0][17]);
                            $modelx->front_qty = 0;
                            $modelx->back_qty = 0;
                            $modelx->back_qty2 = 0;
                            $modelx->total_qty = 0;
                            $modelx->selection =0;
                            $modelx->mode = $rowData[0][19]=='y'?1:0;
                            $modelx->sale_price = $rowData[0][18];
                        
                           if($modelx->save(false)){
                              // $data_save += 1;
                              // $data_all +=1;
                              // array_push($data,['product_id'=>$modelx->id,'qty'=>$modelx->qty,'warehouse'=>1]);
                           }
                         //}
                          
                  //echo $rowData[0][0]."/".$rowData[0][1]."/".$rowData[0][2]."/".$rowData[0][3]."/".$rowData[0][4].'<br />';
                }

                unlink('../web/uploads/files/'.$upfiles);
                }else{
                  //echo "not";
                }
              
           }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelfile' => $modelfile,
            'modelfile2' => $modelfile2,
            'group' => $group,
            'product_type' => $product_type,
            'brand' => $brand,
            'vendor' => $vendor,
            'property' => $property,
            'mode' => $mode,
            'text_search' => $text_search,
        ]);
    }
    public function checkVendor($name){
      $model = \backend\models\Vendor::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Vendor();
        $model_new->name = $name;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
     public function checkCat($name){
      $model = \backend\models\Category::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Category();
        $model_new->name = $name;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
     public function checkBrand($name){
      $model = \backend\models\Brand::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Brand();
        $model_new->name = $name;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
  public function checkUnit($name){
      $model = \backend\models\Unit::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Unit();
        $model_new->name = $name;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
    public function checkType($name,$groupid){
      $model = \backend\models\Producttype::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Producttype();
        $model_new->name = $name;
        $model_new->group_id = $groupid;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
    public function checkProperty($name,$type_id){
      $model = \backend\models\Property::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Property();
        $model_new->name = $name;
        $model_new->status = 1;
        $model_new->type_id = $type_id;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $modelfile = new Modelfile();
        if ($model->load(Yii::$app->request->post()) && $modelfile->load(Yii::$app->request->post())) {
             $uploaded = UploadedFile::getInstances($modelfile, 'file');
             
            if($model->save()){
                if(!empty($uploaded)){
                  foreach($uploaded as $file){
                        $upfiles = time() . "." . $file->getExtension();                        
                        $modelimage = new Productimage();
                        if ($file->saveAs('../web/uploads/images/' . $upfiles)) {
                           $modelimage->image = $upfiles;
                        }
                        $modelimage->product_id = $model->id;
                        $modelimage->save();
                  }
                }
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelfile' => $modelfile,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new StockbalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // $searchModel2 = new ViewStockSearch();
        // $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
        // $dataProvider2->query->where(['product_id'=>$id])->orderby(['created_at'=>SORT_DESC]);

        //$model_trans = \common\models\ViewTrans::find()->where(['product_id'=>$id])->all();

       // $imagelist = Productimage::find()->where(['product_id'=>$id])->all();
         $modelfile = new Modelfile();
        if ($model->load(Yii::$app->request->post()) && $modelfile->load(Yii::$app->request->post())) {
           // $oldlogo = Yii::$app->request->post('old_photo');
            $uploaded = UploadedFile::getInstances($modelfile, 'file');
            // if(!empty($uploaded)){
            //       $upfiles = time() . "." . $uploaded->getExtension();

            //         //if ($uploaded->saveAs('../uploads/products/' . $upfiles)) {
            //         if ($uploaded->saveAs('../web/uploads/logo/' . $upfiles)) {
            //            $model->photo = $upfiles;
            //         }
            // }else{
            //      $model->photo = $oldlogo;
            // }
            if($model->save()){
                if(!empty($uploaded)){
                  foreach($uploaded as $file){
                      //  $upfiles = time() . "." . $file->getExtension();                        
                        $upfiles = $file;                        
                        $modelimage = new Productimage();
                        if ($file->saveAs('../web/uploads/images/' . $upfiles)) {
                           $modelimage->image = $upfiles;
                        }
                        $modelimage->product_id = $model->id;
                        $modelimage->save(false);
                  }
                }else{
                    //$model->photo = $oldlogo;
                }
                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelfile' => $modelfile,
                //'imagelist' => $imagelist,
                'dataProvider' => $dataProvider,
               // 'dataProvider2' => $dataProvider2,
                //'model_trans' => $model_trans,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionBulkdelete()
    {
        if(Yii::$app->request->isAjax){
            $id = explode(",",Yii::$app->request->post('id'));
            if(count($id)>0){
                Product::deleteAll(['id'=>$id]);
            }
        }
    
        return $this->redirect(['index']);
    }
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionShowsubcategory($id){
      $model = \backend\models\Subcategory::find()->where(['category_id' => $id])->all();
        $i = 0;
      if (count($model) > 0) {
          foreach ($model as $value) {
              if($i == 0){
                    //echo "<option>เลือกหมวดย่อย </option>";
                    echo "<option value='$value->id'>$value->name</option>";
                    $i+=1;
              }else{
                    echo "<option value='$value->id'>$value->name</option>";
              }

          }
      } else {
          echo "<option value='0'>-</option>";
      }

    }
    public function actionShowmodel($id){
      $model = \backend\models\Productmodel::find()->where(['brand_id' => $id])->all();
        $i = 0;
      if (count($model) > 0) {
          foreach ($model as $value) {
              if($i == 0){
                    //echo "<option>เลือกรุ่นสินค้า </option>";
                    echo "<option value='$value->id'>$value->name</option>";
                    $i+=1;
              }else{
                    echo "<option value='$value->id'>$value->name</option>";
              }

          }
      } else {
          echo "<option value='0'>-</option>";
      }

    }
    public function actionImportproduct(){
      if(Yii::$app->request->isPost){
        print_r(Yii::$app->request->post());
      }
    }
    public function actionShowtype(){
      if(Yii::$app->request->isAjax){
        $id = Yii::$app->request->post('ids');
        if($id){
          $model = \backend\models\Producttype::find()->where(['group_id'=>$id])->all();
          if($model){
            // echo "<option>เลือกประเภทสินค้า </option>";
             foreach($model as $value){
               echo "<option value='" . $value->id . "'>$value->name</option>";
             }
          }else{
             echo "<option value=''>ไม่พบข้อมูล</option>";
          }
        }
      }
    }
    // public function actionShowtype(){
    //   if(Yii::$app->request->isAjax){
    //     $id = Yii::$app->request->post('ids');
    //     if($id){
    //       $model = \backend\models\Producttype::find()->where(['group_id'=>$id])->all();
    //       if($model){
    //         // echo "<option>เลือกประเภทสินค้า </option>";
    //          foreach($model as $value){
    //            echo "<li>
    //                    <a tabindex='0'>
    //                     <label class='checkbox'>
    //                       <input type='checkbox' value=".$value->id.">
    //                       ".$value->name."
    //                     </label>
    //                    </a>
    //                  </li>";
    //          }
    //       }else{
    //          //echo "<option value=''>ไม่พบข้อมูล</option>";
    //       }
    //     }
    //   }
    // }
    public function actionShowproperty(){
      if(Yii::$app->request->isAjax){
        $id = Yii::$app->request->post('ids');
        if($id){
          $model = \backend\models\Property::find()->where(['type_id'=>$id])->all();
          if($model){
            // echo "<option>เลือกคุณสมบัติ </option>";
             foreach($model as $value){
               echo "<option value='" . $value->id . "'>$value->name</option>";
             }
          }else{
             echo "<option value=''>ไม่พบข้อมูล</option>";
          }
        }
      }
    }
     public function actionShowreport(){
         $brand= '';
        $group= '';
        $product_type = '';
        $vendor = '';
        $property = '';
        $mode = '';
        $text_search = '';  

        if(Yii::$app->request->isPost){
            $group = Yii::$app->request->post('product_group');
            $product_type = Yii::$app->request->post('type');
            $brand = Yii::$app->request->post('brand');
            $vendor = Yii::$app->request->post('vendor');
            $property = Yii::$app->request->post('property');
            $mode = Yii::$app->request->post('mode');
            $text_search = Yii::$app->request->post('text_search');
            //print_r($group);return;
        }

        
      
       $modellist = Product::find()
                     ->andFilterWhere(['like','category_id',$group])
                     ->andFilterWhere(['in','type_id',$product_type])
                     ->andFilterWhere(['in','property_id',$property])
                     ->andFilterWhere(['in','brand_id',$brand])
                     ->andFilterWhere(['in','mode',$mode])
                     ->andFilterWhere(['in','vendor_id',$vendor])
                     ->andFilterWhere(['or',['like','product_code',$text_search],['like','name',$text_search]])->all();
      
      $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                'format' => Pdf::FORMAT_A4, 
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'destination' => Pdf::DEST_BROWSER, 
                'content' => $this->renderPartial('_print',[
                    'list'=>$modellist,  
                    // 'from_date'=> $from_date,
                    // 'to_date' => $to_date,
                    ]),
                //'content' => "nira",
                'cssFile' => '@backend/web/css/pdf.css',
                'options' => [
                    'title' => 'รายงานระหัสินค้า',
                    'subject' => ''
                ],
                'methods' => [
                    'SetHeader' => ['รายงานรหัสสินค้า||Generated On: ' . date("r")],
                    'SetFooter' => ['|Page {PAGENO}|'],
                ]
            ]);
             return $pdf->render();
    }

    public function actionImportupdate(){

       $modelfile = new Modelfile2();
       $uploaded = UploadedFile::getInstance($modelfile,"file");
           if(!empty($uploaded)){
               $upfiles = time() . "." . $uploaded->getExtension();
               if($uploaded->saveAs('../web/uploads/files/'.$upfiles)){
                        $myfile = '../web/uploads/files/'.$upfiles;
                        $inputFileType = \PHPExcel_IOFactory::identify($myfile);
                        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($myfile);

                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();

                          // $maxCell = $sheet->getHighestRowAndColumn();
                          // $data = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);
                          // $data = array_map('array_filter', $data);
                          // $data = array_filter($data); 

                       // echo $highestRow;return;

                        for($row=1;$row <= 300; $row++){
                          $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

                          if($row <=1){
                            continue;
                          }
                          if($rowData[0][0] == ''){
                           // $data_all +=1;
                            continue;
                          }

                                    $modelx = \backend\models\Product::find()->where(['product_code'=>$rowData[0][0]])->one();
                                    if($modelx){
                                        // $modelx->product_code = $rowData[0][0];
                                        // $modelx->name = $rowData[0][1];
                                        // $modelx->description = $rowData[0][1] ;
                                    //    $modelx->category_id = $rowData[0][3];
                                       // $modelx->weight = 0;
                                        // $modelx->category_id = $this->checkCat($rowData[0][11]);
                                        // $modelx->unit_id = $this->checkUnit($rowData[0][22]);
                                        // $modelx->type_id = $this->checkType($rowData[0][12],$modelx->category_id);
                                        // $modelx->property_id = $this->checkProperty($rowData[0][13],$modelx->type_id);
                                        // $modelx->brand_id = $this->checkBrand($rowData[0][14]);
                                       // $modelx->price = 0;
                                       // $modelx->product_start = $rowData[0][3];
                                        $modelx->sale_qty = $rowData[0][4];
                                        $modelx->purch_qty = $rowData[0][5];
                                        $modelx->return_qty = $rowData[0][6];
                                        $modelx->adjust_qty = $rowData[0][7];
                                        $modelx->cost_sum = $rowData[0][9];
                                        $modelx->cost = $rowData[0][10];
                                        $modelx->qty = $rowData[0][8];
                                       // $modelx->min_qty = 0;
                                       // $modelx->max_qty = 0;
                                       // $modelx->status = 1;
                                        // $modelx->group_id = $this->checkCat($rowData[0][11]);
                                        // $modelx->vendor_id = $this->checkVendor($rowData[0][21]);
                                        $modelx->front_qty = $rowData[0][15];
                                        $modelx->back_qty = $rowData[0][16];
                                        $modelx->back_qty2 = $rowData[0][17];
                                        $modelx->total_qty = $rowData[0][18];
                                        $modelx->selection = $rowData[0][19];
                                        $modelx->mode = $rowData[0][19]=='y'?1:0;
                                        $modelx->sale_price = $rowData[0][23];
                                    
                                       $modelx->save(false);
                                    }
                                  
                          //echo $rowData[0][0]."/".$rowData[0][1]."/".$rowData[0][2]."/".$rowData[0][3]."/".$rowData[0][4].'<br />';
                        }
                        // unlink('../web/uploads/files/'.$upfiles);
                        return $this->redirect(['index']);
              }

           }else{
                  return $this->redirect(['index']);
          }
    }
      
      public function actionGenpo(){
        $prodid = Yii::$app->request->post('listid');
        $vendor_id = Yii::$app->request->post('vendor_id');
        $pid = explode(',', $prodid);
        
        
        if(count($pid)>0 && $pid[0]!=''){
            $model = new \backend\models\Purchaseorder();
            $model->purchase_order = $model::getLastNo();
            $model->vendor_id = $vendor_id ;
            $model->purchase_date = time();
            $model->status = 1;
            if($model->save()){
               for($i=0;$i<=count($pid)-1;$i++){
                  $modelline = new \backend\models\Purchaseorderline();
                  $modelline->product_id = $pid[$i];
                  $modelline->qty = 1;
                  $modelline->purchase_order_id = $model->id;
                  $modelline->price = $this->getPrice($pid[$i]);
                  $modelline->line_amount = $this->getPrice($pid[$i]) * 1;
                  $modelline->status = 1;
                  $modelline->save(false);
               }
               $this->updateSumpo($model->id);
            }

            $session = Yii::$app->session;
            $session->setFlash('success','สร้างใบสั่งซื้อเรียบร้อยแล้ว');

            return $this->redirect(['index']);
        }else{
            $session = Yii::$app->session;
            $session->setFlash('error','ไม่มีรายการให้สร้างใบสั่งซื้อ');

            return $this->redirect(['index']);
        }

      }
      public function getPrice($id){
        $model = \backend\models\Product::find()->where(['id'=>$id])->one();
        if($model){
          return $model->cost;
        }else{
          return 0;
        }
      }
      public function updateSumpo($id){
        $qty = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$id])->sum('line_amount');
        $model = \backend\models\Purchaseorder::find()->where(['id'=>$id])->one();
        if($model){
          $model->purchase_amount = $qty;
          $model->save(false);
        }
      }

}

              
               

               