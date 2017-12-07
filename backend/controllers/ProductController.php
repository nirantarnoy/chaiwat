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
use backend\models\Productimage;
use backend\models\StockbalanceSearch;
use backend\models\ViewStockSearch;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
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
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $modelfile = new Modelfile();

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

                for($row=1;$row <= $highestRow; $row++){
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
                            $modelx->type_id = $this->checkType($rowData[0][13]);
                           // $modelx->property_id = $this->checkProperty($rowData[0][13]);
                            $modelx->brand_id = $this->checkBrand($rowData[0][14]);
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
                            $modelx->vendor_id = $this->checkVendor($rowData[0][20]);
                           $modelx->front_qty = $rowData[0][15];
                           $modelx->back_qty = $rowData[0][16];
                           $modelx->back_qty2 = $rowData[0][17];
                           $modelx->total_qty = $rowData[0][18];
                           $modelx->selection = $rowData[0][19];
                        
                           if($modelx->save(false)){
                              $data_save += 1;
                              $data_all +=1;
                              array_push($data,['product_id'=>$modelx->id,'qty'=>$modelx->qty,'warehouse'=>1]);
                           }
                         // }
                          
                  //echo $rowData[0][0]."/".$rowData[0][1]."/".$rowData[0][2]."/".$rowData[0][3]."/".$rowData[0][4].'<br />';
                }

                
                }else{
                  //echo "not";
                }
              
           }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelfile' => $modelfile,
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
    public function checkType($name){
      $model = \backend\models\Producttype::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Producttype();
        $model_new->name = $name;
        $model_new->status = 1;
        if($model_new->save(false)){
          return $model_new->id;
        }
      }
    }
    public function checkProperty($name){
      $model = \backend\models\Property::find()->where(['name'=>$name])->one();
      if(count($model)>0){
        return $model->id;
      }else{
        $model_new = new \backend\models\Property();
        $model_new->name = $name;
        $model_new->status = 1;
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
                    echo "<option>เลือกหมวดย่อย </option>";
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
                    echo "<option>เลือกรุ่นสินค้า </option>";
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

}