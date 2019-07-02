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
use backend\models\Purchaseorder;
use backend\common\cart;
use yii\web\Session;
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
                    'update' => ['POST','GET'],
                     'update2' => ['POST','GET'],
                    'view' => ['POST','GET'],
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
        $code_search = '';
        $movement = '';
        $movement2 = '';

        $sale_sum = 0;
        $purch_sum = 0;
        $last_update = '';

        $session = Yii::$app->session;

        if(Yii::$app->request->isPost){
            $group = Yii::$app->request->post('product_group');
            $product_type = Yii::$app->request->post('type');
            $brand = Yii::$app->request->post('brand');
            $vendor = Yii::$app->request->post('vendor');
            $property = Yii::$app->request->post('property');
            $mode = Yii::$app->request->post('mode');
            $text_search = Yii::$app->request->post('text_search');
            $code_search = Yii::$app->request->post('code_search');
            $movement = Yii::$app->request->post('movement');

            if(count($movement)>1){
              $movement2 = '';
            }else{
              $movement2 = $movement[0];
            }

           // echo $movement2;return;

         //  print_r($movement);return;
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
            $session['movement'] = $movement2;
            $session['text_search'] = $text_search;
            $session['code_search'] = $code_search;
        }

       // if(isset($session['group'])){
          $brand=  $session['brand'];
          $group=  $session['group'];
          $product_type =  $session['product_type'];
          $vendor =  $session['vendor'];
          $property =  $session['property'];
          $mode =  $session['mode'];
          $movement2 =  $session['movement'];
         // $movement =  $session['movement'];
          $text_search =  $session['text_search'];
          $code_search =  $session['code_search'];

       // }

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //print_r(Yii::$app->request->queryParams);

         $dataProvider->query->andFilterWhere(['in','category_id',$session['group']])
                    ->andFilterWhere(['in','type_id',$session['product_type']])
                     ->andFilterWhere(['in','property_id',$session['property']])
                     ->andFilterWhere(['in','brand_id',$session['brand']])
                     ->andFilterWhere(['in','mode',$session['mode']])
                     ->andFilterWhere(['in','vendor_id',$session['vendor']])
                     ->andFilterWhere(['like','product_code',$session['code_search']])
                     ->andFilterWhere(['like','name',$session['text_search']]);
        if($movement2 == 2 ){
            //echo "noo";
             $dataProvider->query->andFilterWhere(['sale_qty'=>0])->andFilterWhere(['purch_qty'=>0]);
        }else if($movement2 == 1){
             $dataProvider->query->andFilterWhere(['or',['>','sale_qty',0],['>','purch_qty',0]]);
        }else{
          //echo "nid";
        }


        //$dataProvider->pagination->pageSize = 10;


//       $sale_sum = Product::find()->andfilterWhere(['or',['like','product_code',$session['code_search']],['like','name',$session['text_search']]])
//                                  ->andFilterWhere(['in','category_id',$session['group']])
//                                   ->andFilterWhere(['in','type_id',$session['product_type']])
//                                   ->andFilterWhere(['in','property_id',$session['property']])
//                                   ->andFilterWhere(['in','brand_id',$session['brand']])
//                                   ->andFilterWhere(['in','mode',$session['mode']])
//                                   ->andFilterWhere(['in','vendor_id',$session['vendor']])
//                                   ->sum('sale_qty');
//       $purch_sum = Product::find()->andfilterWhere(['or',['like','product_code',$session['code_search']],['like','name',$session['text_search']]])
//                                  ->andFilterWhere(['in','category_id',$session['group']])
//                                   ->andFilterWhere(['in','type_id',$session['product_type']])
//                                   ->andFilterWhere(['in','property_id',$session['property']])
//                                   ->andFilterWhere(['in','brand_id',$session['brand']])
//                                   ->andFilterWhere(['in','mode',$session['mode']])
//                                   ->andFilterWhere(['in','vendor_id',$session['vendor']])
//                                   ->sum('purch_qty');

        $modelfile = new Modelfile();
        $modelfile2 = new Modelfile2();

       //  $model_select = $dataProvider->getTotalCount();//$dataProvider->getModels();
         $dataProvider->pagination->pageSize = 50;

         /// $dataProvider->query->orderby(['name'=>SORT_ASC]);

        if($modelfile->load(Yii::$app->request->post())){
           $uploaded = UploadedFile::getInstance($modelfile,"file");
           if(!empty($uploaded)){

              // $data = [];
              // $data_save = 0;
              // $data_fail = [];
              // $data_all = 0;
              $upfiles = time() . "." . $uploaded->getExtension();
               if($uploaded->saveAs('../web/uploads/files/'.$upfiles)){
                 //echo "okk";return;
                  $myfile = '../web/uploads/files/'.$upfiles;


                    $file = fopen($myfile, "r");
                    fwrite($file, "\xEF\xBB\xBF");
                     // header('Content-Type: text/html; charset=UTF-8');
                     // iconv_set_encoding("internal_encoding", "UTF-8");
                     // iconv_set_encoding("output_encoding", "UTF-8");
                     // setlocale(LC_ALL, 'th_TH.utf8');
                   setlocale ( LC_ALL, 'th_TH.TIS-620' );
                    $i = -1;
                     while (($rowData = fgetcsv($file, 10000, ",")) !== FALSE)
                     {
                          $i+=1;
                          if($rowData[0] =='' || $i == 0){
                            continue;
                          }

                         // $rowData = array_map('utf8_encode', $rowData);

                          // if( mb_detect_encoding($rowData[1], 'UTF-8','auto') !== false ){
                          //     echo "utf-8";
                          //     echo $rowData[1];
                          // }else{
                          //    $x = utf8_encode($rowData[1]);
                          //    echo $x;
                          // }
                          // break;
                         echo $rowData[0];return;

                          $modelprod = \backend\models\Product::find()->where(['product_code'=>$rowData[0]])->one();
                          if(count($modelprod)>0){
                            // $data_all +=1;
                            // array_push($data_fail,['name'=>$rowData[0][1]]);
                            continue;
                          }else{
                              echo $rowData[0]; return;
                          }
                         $sale_qty_new = 0;
                         $purch_qty_new = 0;
                         $return_qty_new = 0;
                         $adj_qty_new = 0;
                         $cost_sum_new = 0;
                         $cost_new = 0;
                         $qty_new = 0;
                         $sale_price_new = 0;

                         if($rowData[5] != "-"){
                             $sale_qty_new = str_replace(',','', $rowData[5]);
                         }
                         if($rowData[6] != "-"){
                             $purch_qty_new = str_replace(',','', $rowData[6]);
                         }
                         if($rowData[7] != "-"){
                             $return_qty_new = str_replace(',','', $rowData[7]);
                         }
                         if($rowData[8] != "-"){
                             $adj_qty_new = str_replace(',','', $rowData[8]);
                         }
                         if($rowData[10] != "-"){
                             $cost_sum_new = str_replace(',','', $rowData[10]);
                         }
                         if($rowData[11] != "-"){
                             $cost_new = str_replace(',','', $rowData[11]);
                         }
                         if($rowData[9] != "-"){
                             $qty_new = str_replace(',','', $rowData[9]);
                         }

                            $modelx = new \backend\models\Product();
                            $modelx->product_code = $rowData[0];
                            $modelx->name = $rowData[1];
                            $modelx->description = $rowData[1] ;
                        //    $modelx->category_id = $rowData[0][3];
                            $modelx->weight = 0;
                            $modelx->category_id = $this->checkCat($rowData[12]);
                            $modelx->unit_id = $this->checkUnit($rowData[2]);
                            $modelx->type_id = $this->checkType($rowData[13],$modelx->category_id);
                            $modelx->property_id = $this->checkProperty($rowData[14],$modelx->type_id);
                            $modelx->brand_id = $this->checkBrand($rowData[15]);
                            $modelx->price = 0;
                            $modelx->product_start = str_replace(',','', $rowData[4]);
                            $modelx->sale_qty = $sale_qty_new;
                            $modelx->purch_qty = $purch_qty_new;
                            $modelx->return_qty = $return_qty_new;
                            $modelx->adjust_qty = $adj_qty_new;
                            $modelx->cost_sum = $cost_sum_new;
                            $modelx->cost = $cost_new;
                            $modelx->qty = $qty_new;
                            $modelx->min_qty = 0;
                            $modelx->max_qty = 0;
                            $modelx->status = 1;
                            $modelx->group_id = $this->checkCat($rowData[12]);
                            $modelx->vendor_id = $this->checkVendor($rowData[17]);
                            $modelx->front_qty = 0;
                            $modelx->back_qty = 0;
                            $modelx->back_qty2 = 0;
                            $modelx->total_qty = 0;
                            $modelx->selection =0;
                            $modelx->mode = $rowData[19]=='y'?1:0;
                            $modelx->sale_price = str_replace(',','', $rowData[18]);;

                           if($modelx->save(false)){
                              // $data_save += 1;
                              // $data_all +=1;
                              // array_push($data,['product_id'=>$modelx->id,'qty'=>$modelx->qty,'warehouse'=>1]);
                           }
                        // }

                     }
                     fclose($file);



                unlink('../web/uploads/files/'.$upfiles);
              //  print_r($data_insert);return;

                }else{
                  //echo "not";
                }

           }
        }

        $modellastupdate = Product::find()->max('updated_at');

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
            'movement' => $movement,
            'text_search' => $text_search,
            'code_search' => $code_search,
            'sale_sum'=> $sale_sum,
            'purch_sum'=> $purch_sum,
          //  'model_select'=> $model_select,
            'last_update'=> $modellastupdate,
        ]);
    }
    function convert( $str ) {
        return iconv( "Windows-1252", "UTF-8", $str );
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
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }
     public function actionView()
    {
       $session = Yii::$app->session;
        return $this->render('view', [
            'model' => $this->findModel($session['pid']),
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
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //     $searchModel = new StockbalanceSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     // $searchModel2 = new ViewStockSearch();
    //     // $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);
    //     // $dataProvider2->query->where(['product_id'=>$id])->orderby(['created_at'=>SORT_DESC]);

    //     //$model_trans = \common\models\ViewTrans::find()->where(['product_id'=>$id])->all();

    //    // $imagelist = Productimage::find()->where(['product_id'=>$id])->all();
    //      $modelfile = new Modelfile();
    //     if ($model->load(Yii::$app->request->post()) && $modelfile->load(Yii::$app->request->post())) {
    //        // $oldlogo = Yii::$app->request->post('old_photo');
    //         $uploaded = UploadedFile::getInstances($modelfile, 'file');
    //         // if(!empty($uploaded)){
    //         //       $upfiles = time() . "." . $uploaded->getExtension();

    //         //         //if ($uploaded->saveAs('../uploads/products/' . $upfiles)) {
    //         //         if ($uploaded->saveAs('../web/uploads/logo/' . $upfiles)) {
    //         //            $model->photo = $upfiles;
    //         //         }
    //         // }else{
    //         //      $model->photo = $oldlogo;
    //         // }
    //         if($model->save()){
    //             if(!empty($uploaded)){
    //               foreach($uploaded as $file){
    //                   //  $upfiles = time() . "." . $file->getExtension();
    //                     $upfiles = $file;
    //                     $modelimage = new Productimage();
    //                     if ($file->saveAs('../web/uploads/images/' . $upfiles)) {
    //                        $modelimage->image = $upfiles;
    //                     }
    //                     $modelimage->product_id = $model->id;
    //                     $modelimage->save(false);
    //               }
    //             }else{
    //                 //$model->photo = $oldlogo;
    //             }
    //             return $this->redirect(['index']);
    //         }
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //             'modelfile' => $modelfile,
    //             //'imagelist' => $imagelist,
    //             'dataProvider' => $dataProvider,
    //            // 'dataProvider2' => $dataProvider2,
    //             //'model_trans' => $model_trans,
    //         ]);
    //     }
    // }

    public function actionUpdate()
    {
        $session = Yii::$app->session;
        $model = $this->findModel($session['pid']);
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
                return $this->redirect(['index']);
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

     public function actionUpdate2()
    {
       $id = Yii::$app->request->post('id');
        if($id){
          $session = Yii::$app->session;
          $session['pid'] = $id;
          return $this->redirect(['update']);
        }
    }
     public function actionView2()
    {
       $id = Yii::$app->request->post('id');
        if($id){
          $session = Yii::$app->session;
          $session['pid'] = $id;
          return $this->redirect(['view']);
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
     public function actionAlldelete()
    {
        //Product::deleteAll();
      // $session = Yii::$app->session;
      // if($session['group']!=''){
      //   $res = Product::find()->Where(['in','category_id',$session['group']])
      //                ->andFilterWhere(['in','type_id',$session['product_type']])
      //                ->andFilterWhere(['in','property_id',$session['property']])
      //                ->andFilterWhere(['in','brand_id',$session['brand']])
      //                ->andFilterWhere(['in','mode',$session['mode']])
      //                ->andFilterWhere(['in','vendor_id',$session['vendor']])
      //                ->andFilterWhere(['or',['like','product_code',$session['text_search']],['like','name',$session['text_search']]])->all();
      // if(count($res)>0){
      //   foreach($res as $data){
      //     Product::deleteAll(['id'=>$data->id]);
      //   }
      // }
      // }

      if(Yii::$app->request->isAjax){
        $ids = Yii::$app->request->post('id');
        if($ids !=''){
         // return $ids;
          $idd = explode(',', $ids);
          Product::deleteAll(['id'=>$idd]);
        }
      }


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
      $model = \backend\models\Subcategory::find()->where(['category_id' => $id])->orderby(['name'=>SORT_ASC])->all();
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
      $model = \backend\models\Productmodel::find()->where(['brand_id' => $id])->orderby(['name'=>SORT_ASC])->all();
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
          $model = \backend\models\Producttype::find()->where(['group_id'=>$id])->orderby(['name'=>SORT_ASC])->all();
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
          $model = \backend\models\Property::find()->where(['type_id'=>$id])->orderby(['name'=>SORT_ASC])->all();
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
     public function actionShowvendor(){
      if(Yii::$app->request->isAjax){
        $groupid = Yii::$app->request->post('groupid');
        $typeid = Yii::$app->request->post('typeid');
        $propertyid = Yii::$app->request->post('propertyid');

        $product = Product::find()->where(['group_id'=>$groupid,'type_id'=>$typeid,'property_id'=>$propertyid])->all();
        if($product){
          $id = [];
          foreach($product as $data){
            array_push($id,$data->vendor_id);
          }

            if(count($id)>0){

              $model = \backend\models\Vendor::find()->where(['id'=>$id])->orderby(['name'=>SORT_ASC])->all();
              if($model){
                // echo "<option>เลือกคุณสมบัติ </option>";
                 foreach($model as $value){
                   echo "<option value='" . $value->id . "'>$value->name</option>";
                 }
              }else{
                 echo "<option value=''>ไม่พบข้อมูล</option>";
              }
            }
          }else{
             echo "<option value=''>ไม่พบข้อมูล</option>";
          }

      }
    }
    public function actionShowbrand(){
      if(Yii::$app->request->isAjax){
        $groupid = Yii::$app->request->post('groupid');
        $typeid = Yii::$app->request->post('typeid');
        $propertyid = Yii::$app->request->post('propertyid');
        $vendorid = Yii::$app->request->post('vendorid');

        $product = Product::find()->where(['group_id'=>$groupid,'type_id'=>$typeid,'property_id'=>$propertyid,'vendor_id'=>$vendorid])->all();
        if($product){
          $id = [];
          foreach($product as $data){
            array_push($id,$data->brand_id);
          }

            if(count($id)>0){

              $model = \backend\models\Brand::find()->where(['id'=>$id])->orderby(['name'=>SORT_ASC])->all();
              if($model){
                // echo "<option>เลือกคุณสมบัติ </option>";
                 foreach($model as $value){
                   echo "<option value='" . $value->id . "'>$value->name</option>";
                 }
              }else{
                 echo "<option value=''>ไม่พบข้อมูล</option>";
              }
            }
          }else{
             echo "<option value=''>ไม่พบข้อมูล</option>";
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
        $code_search = '';
        $text_search = '';
        $movement = '';
        $movement2 = '';
        $sortparam = '';

        if(Yii::$app->request->isPost){
            $group = Yii::$app->request->post('product_group');
            $product_type = Yii::$app->request->post('type');
            $brand = Yii::$app->request->post('brand');
            $vendor = Yii::$app->request->post('vendor');
            $property = Yii::$app->request->post('property');
            $mode = Yii::$app->request->post('mode');
            $code_search = Yii::$app->request->post('code_search');
            $text_search = Yii::$app->request->post('text_search');
            $movement = Yii::$app->request->post('movement');
            $sortparam = Yii::$app->request->post('sort_report');

            if(count($movement)>1){
              $movement2 = '';
            }else{
              $movement2 = $movement[0];
            }
          //  print_r($group);return;
        }

       $modellist = Product::find()
                     ->andFilterWhere(['in','category_id',$group])
                     ->andFilterWhere(['in','type_id',$product_type])
                     ->andFilterWhere(['in','property_id',$property])
                     ->andFilterWhere(['in','brand_id',$brand])
                     ->andFilterWhere(['in','mode',$mode])
                     ->andFilterWhere(['in','vendor_id',$vendor])
                      ->andFilterWhere(['like','product_code',$code_search])
                     ->andFilterWhere(['like','name',$text_search]);
       if($movement2 == 2){
             $modellist=$modellist->andFilterWhere(['sale_qty'=>0])->andFilterWhere(['purch_qty'=>0]);
        }else if($movement2 == 1){
             $modellist=$modellist->andFilterWhere(['or',['>','sale_qty',0],['>','purch_qty',0]]);
        }else{

        }

        if($sortparam != ''){
          if(substr($sortparam,0,1)=="-"){
             $newsort = substr($sortparam,strpos($sortparam, "-") + 1, strlen($sortparam));
             $modellist = $modellist->orderby([$newsort=>SORT_DESC])->all();
           }else{
             $modellist = $modellist->orderby([$sortparam=>SORT_ASC])->all();
           }

        }else{
          $modellist = $modellist->orderby(['name'=>SORT_ASC])->all();
        }


         //echo count($modellist);return;

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
               //echo $upfiles;return;
               if($uploaded->saveAs('../web/uploads/files/'.$upfiles)){

                    $myfile = '../web/uploads/files/'.$upfiles;

                    $file = fopen($myfile, "r");
                    fwrite($file, "\xEF\xBB\xBF");
                     // header('Content-Type: text/html; charset=UTF-8');
                     // iconv_set_encoding("internal_encoding", "UTF-8");
                     // iconv_set_encoding("output_encoding", "UTF-8");
                     // setlocale(LC_ALL, 'th_TH.utf8');
                    setlocale ( LC_ALL, 'th_TH.TIS-620' );
                    $i = -1;

                    $res = 0;
                    $x ='';
                     while (($rowData = fgetcsv($file,1000,",")) !== FALSE)
                     {
                          $i+=1;
                          if($rowData[0] =='' || $i == 0){
                            continue;
                          }

//                         $rowData = preg_replace('/(\\\",)/','\\ ",',$rowData);
//                         $rowData = preg_replace('/(\\\"("?),)/',' ',$rowData);
//                         $data[] = str_getcsv($rowData);


                          $x = $rowData[0];
                        //  echo str_replace('\\','',$x[0]); return;
                        // var_dump($prodcode);

                         $sale_qty_new = 0;
                         $purch_qty_new = 0;
                         $return_qty_new = 0;
                         $adj_qty_new = 0;
                         $cost_sum_new = 0;
                         $cost_new = 0;
                         $qty_new = 0;

                         if($rowData[5] != "-"){
                             $sale_qty_new = str_replace(',','', $rowData[5]);
                         }
                         if($rowData[6] != "-"){
                             $purch_qty_new = str_replace(',','', $rowData[6]);
                         }
                         if($rowData[7] != "-"){
                             $return_qty_new = str_replace(',','', $rowData[7]);
                         }
                         if($rowData[8] != "-"){
                             $adj_qty_new = str_replace(',','', $rowData[8]);
                         }
                         if($rowData[10] != "-"){
                             $cost_sum_new = str_replace(',','', $rowData[10]);
                         }
                         if($rowData[11] != "-"){
                             $cost_new = str_replace(',','', $rowData[11]);
                         }
                         if($rowData[9] != "-"){
                             $qty_new = str_replace(',','', $rowData[9]);
                         }

                          $modelx = \backend\models\Product::find()->where(['product_code'=> $x])->one();
                          if(count($modelx)>0){
                             // echo "OK";
                              //return;
                             //echo str_replace(',','', $rowData[5]);return;
                              $qty = str_replace(',','', $rowData[5]);
                              $modelx->product_start = str_replace(',','', $rowData[4]);
                              $modelx->sale_qty = $sale_qty_new;
                             // $modelx->sale_qty = str_replace(',','', $rowData[5]);;
                              $modelx->purch_qty = $purch_qty_new;
                              $modelx->return_qty = $return_qty_new;
                              $modelx->adjust_qty = $adj_qty_new;
                              $modelx->cost_sum = $cost_sum_new;
                              $modelx->cost = $cost_new;
                              $modelx->qty = $qty_new;
                             // $modelx->mode = $rowData[19]=='y'?1:0;
                             // $modelx->sale_price = str_replace(',','', $rowData[18]);;

                            //  $modelx->min_qty = 0;
                            //  $modelx->max_qty = 0;
                            //  $modelx->status = 1;
                            //  $modelx->group_id = $this->checkCat($rowData[12]);
                            //  $modelx->vendor_id = $this->checkVendor($rowData[17]);
                              // $modelx->front_qty = 0;
                              // $modelx->back_qty = 0;
                              // $modelx->back_qty2 = 0;
                              // $modelx->total_qty = 0;
                              // $modelx->selection =0;
                            //  $modelx->mode = $rowData[19]=='y'?1:0;
                             // $modelx->sale_price = $rowData[18];

                           if($modelx->save(false)){
                             $res +=1;
                           }
                          }else{
                               //echo "not found";
                          }



                     }
                     fclose($file);
                        unlink('../web/uploads/files/'.$upfiles);
                        if($res > 0){
                            return $this->redirect(['index']);
                        }

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

            $session['new_poid'] = $model->id;
            return $this->redirect(['purchaseorder/update2']);
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
      public function actionGetnote(){
        if(Yii::$app->request->isAjax){
          $id = Yii::$app->request->post('ids');
          if($id){
            $model = Product::find()->where(['id'=>$id])->one();
            if($model){
              return $model->notes;
            }else{
              return '';
            }

          }else{
            return '';
          }
        }
      }
      public function actionAdditemcart(){
        if(Yii::$app->request->isAjax){
          $id = Yii::$app->request->post('id');
          if($id){
            $model = Purchaseorder::find()->where(['id'=>$id])->one();
            if($model){
             // return "ok";
              $session = Yii::$app->session;
              $session['purchase_id']=$model->id;
              $session['purchase_no']=$model->purchase_order;
            }
            return $this->redirect(['index']);
          }
        }
      }

}




