<?php

namespace backend\controllers;

use Yii;
use backend\models\Purchaseorder;
use backend\models\PurchaseorderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Trans;
use yii\helpers\Json;
use backend\models\Product;
use yii\web\Session;
use Yii\headers\ArrayHelper;
use backend\common\cart;
use kartik\mpdf\Pdf;
use yii\web\UrlManager;

$session = new Session();
$session->open();

/**
 * PurchaseorderController implements the CRUD actions for Purchaseorder model.
 */
class PurchaseorderController extends Controller
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
                    'submitcart'=>['POST','GET'],
                    'displayprint'=>['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Purchaseorder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseorderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchaseorder model.
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
     * Creates a new Purchaseorder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Purchaseorder();

        if ($model->load(Yii::$app->request->post())) {
             $prodid = Yii::$app->request->post('product_id');
            $qty = Yii::$app->request->post('qty');
            $price = Yii::$app->request->post('price');
            $lineamt = Yii::$app->request->post('line_amount');
              $model->purchase_date = strtotime($model->purchase_date);
              $model->status = 1;
               $model->created_by = Yii::$app->user->identity->id;
            if($model->save()){
                if(count($prodid)>0){
                    for($i=0;$i<=count($prodid)-1;$i++){
                        $modelline = new \backend\models\Purchaseorderline();
                        $modelline->purchase_order_id = $model->id;
                        $modelline->product_id = $prodid[$i];
                        $modelline->qty = $qty[$i];
                        $modelline->price = $price[$i];
                        $modelline->line_amount=$lineamt[$i];
                        $modelline->save(false);
                    }
                }
                $this->updateAmount($model->id);
                 return $this->redirect(['update', 'id' => $model->id]);
            }
    
        }

        return $this->render('create', [
            'model' => $model,
            'runno' => $model::getLastNo(),
        ]);
    }

    /**
     * Updates an existing Purchaseorder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         $modelline = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$id])->all();
        if ($model->load(Yii::$app->request->post())) {
            $prodid = Yii::$app->request->post('product_id');
            $qty = Yii::$app->request->post('qty');
            $price = Yii::$app->request->post('price');
            $lineamt = Yii::$app->request->post('line_amount');

            $model->purchase_date = strtotime($model->purchase_date);
            if($model->save()){
                \backend\models\Purchaseorderline::deleteAll(['purchase_order_id'=>$id]);
                if(count($prodid)>0){
                    for($i=0;$i<=count($prodid)-1;$i++){
                        $modelline = new \backend\models\Purchaseorderline();
                        $modelline->purchase_order_id = $model->id;
                        $modelline->product_id = $prodid[$i];
                        $modelline->qty = $qty[$i];
                        $modelline->price = $price[$i];
                        $modelline->line_amount=$lineamt[$i];
                        $modelline->save(false);
                    }
                }
                $this->updateAmount($id);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelline' => $modelline,
        ]);
    }
    public function actionUpdate2()
    {
        $session = Yii::$app->session;
        $id = $session['new_poid'];
        $model = $this->findModel($id);
         $modelline = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$id])->all();
        if ($model->load(Yii::$app->request->post())) {
            $prodid = Yii::$app->request->post('product_id');
            $qty = Yii::$app->request->post('qty');
            $price = Yii::$app->request->post('price');
            $lineamt = Yii::$app->request->post('line_amount');

            $model->purchase_date = strtotime($model->purchase_date);
            if($model->save()){
                \backend\models\Purchaseorderline::deleteAll(['purchase_order_id'=>$id]);
                if(count($prodid)>0){
                    for($i=0;$i<=count($prodid)-1;$i++){
                        $modelline = new \backend\models\Purchaseorderline();
                        $modelline->purchase_order_id = $model->id;
                        $modelline->product_id = $prodid[$i];
                        $modelline->qty = $qty[$i];
                        $modelline->price = $price[$i];
                        $modelline->line_amount=$lineamt[$i];
                        $modelline->save(false);
                    }
                }
                $this->updateAmount($id);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelline' => $modelline,
        ]);
    }

    /**
     * Deletes an existing Purchaseorder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionProductitem(){
        $model = Product::find()->where(['vendor_id'=>148])->all();
        return $this->renderAjax('_productlist',['model'=>$model]);
    }

    /**
     * Finds the Purchaseorder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchaseorder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchaseorder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function updateAmount($id){
        $model = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$id])->sum('line_amount');
        if($model){
            $model_order = \backend\models\Purchaseorder::find()->where(['id'=>$id])->one();
            if($model_order){
                $model_order->purchase_amount = $model;
                $model_order->save(false);
            }
        }
    }
    public function actionReceivelist(){
        if(Yii::$app->request->isAjax){
            $po_no = Yii::$app->request->post("po");
           // return $po_no;
            if($po_no !=''){
                $modelpo = Purchaseorder::find()->where(['purchase_order'=>$po_no])->one();
                if($modelpo){
                    $model = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$modelpo->id])->all();
                    if($model){
                        return $this->renderPartial('_receive',['model'=>$model]);
                    }
                }
            }
        }
    }
    public function actionReceivepurchase(){
         if(Yii::$app->request->isPost){
            $poid = Yii::$app->request->post("poid");
            $product_id = Yii::$app->request->post("product_id");
            $qty = Yii::$app->request->post("qty");
            $wh = Yii::$app->request->post("warehouseid");
            
            if(count($poid)>0){
                for($i=0;$i<=count($product_id)-1;$i++){
                    $data = [];
                    if($qty[$i] > 0){
                         array_push($data,['product_id'=>$product_id[$i],'qty'=>$qty[$i],'warehouse'=>$wh[$i]]);
                    }
                   
                   // echo $data['product_id'];return;
                   
                }
                 $x =Trans::createTrans($data,0,$this->getPono($poid[0]));
                    if($x){
                        $this->updatePostatus($poid[0]);
                        $session = Yii::$app->session;
                        $session->setFlash('msg','บันทึกรายการเรียบร้อย');
                        return $this->redirect(['index']);
                    }
            }
         }
    }
    public function getPono($id){
        $model = Purchaseorder::find()->where(['id'=>$id])->one();
        return count($model)>0?$model->purchase_order:'';
    }
    public function updatePostatus($id){
        $poid = $this->getPono($id);
        $res = 0;
        $modelpo_qty = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$id])->all();
        if($modelpo_qty){
            foreach($modelpo_qty as $value){
                $order_qty = $value->qty;
                $receive_qty = \common\models\ViewTrans::find()->where(['reference'=>$poid,'product_id'=>$value->product_id])->sum('qty');
                if($receive_qty >= $order_qty){
                    $res +=1;
                }
            }

            if($res == count($modelpo_qty)){
                $model = Purchaseorder::find()->where(['id'=>$id])->one();
                $model->status = 2; //success
                $model->save(false);
            }
        }
    }
    public function actionAddline(){
    $data = Yii::$app->request->post('data');
    return $this->renderPartial('_addline',['data'=>$data]);
}
public function actionProductlist($q = null) {
      $query = $q;
      $model = \backend\models\Product::find()->where(['like','product_code',$query])->orFilterWhere(['like','name',$query])->all();
      if($model){
              echo Json::encode($model);
      }

}
    public function actionAddpick(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('ids');
            if($id!=''){
                $ids = explode(',', $id);
                 
                for($i=0;$i<=count($ids)-1;$i++){
                     $model = Product::getProductInfo($ids[$i]);
                     $cart = new cart();
                     $cart->addCart($ids[$i],['prodid'=>$model->product_code,'name'=>$model->name,'price'=>$model->price,'qty'=>1]);
                }
                $session = Yii::$app->session;
                if(isset($session['cart'])){
                        $infocart = $session['cart'];
                        $session->setFlash('msg_addcart','เพิ่มสินค้าเรียบร้อย');
                         return count($infocart);
                }else{
                        //return 50;
                }
            }
        }
    }
    // public function actionShowcart(){
    //     //$session = Yii::$app->session;
    //     return $this->redirect(['purchaseorder/showitem']);
    // }
    public function actionShowitem(){
        return $this->renderAjax('_cart');
    }
    public function actionRemoveorder(){
        $session = Yii::$app->session;
        unset($session['cart']);
    }
    public function actionSubmitcart(){
           $session = Yii::$app->session;
           if(isset($session['cart'])){
              if(isset($session['purchase_id'])){
                       $cart = $session['cart'];
                       $poid = $session['purchase_id'];
                       if(count($cart)>0 && $poid !=''){
                            foreach($cart as $kay =>$value){
                                 $prodid = Product::getProdid($value['product_id']);
                                 $modelx = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$poid,'product_id'=>$prodid])->one();
                                 if($modelx){
                                    $modelx->qty = $modelx->qty + $value['qty'];
                                    $modelx->line_amount= $modelx->qty *  $modelx->price;
                                    //$modelx->qty = 10;
                                    $modelx->save(false);
                                 }else{
                                    $modelline = new \backend\models\Purchaseorderline();
                                    $modelline->purchase_order_id = $poid;
                                    $modelline->product_id = Product::getProdid($value['product_id']);
                                    $modelline->qty = $value['qty'];
                                    $modelline->price =$value['price'];
                                    $modelline->line_amount=$value['qty'] * $value['price'];
                                    $modelline->save(false);
                                 }
                            }
                        }else{
                            return $this->redirect(['product/index']);
                        }
                         $this->updateAmount($poid);
                         unset($session['cart']);
                         unset($session['purchase_id']);
                         unset($session['purchase_no']);
                         return $this->redirect(['update', 'id' => $poid]);

              }else{
                   $cart = $session['cart'];
                   $vendorid = 0;
                   $i=0;
                   foreach($cart as $key =>$value){
                     if($i==0){
                        $modelx = Product::getProductInfo($key);
                        if($modelx){
                            $vendorid = $modelx->vendor_id;
                        }
                     }else{
                        continue;
                     }
                     $i+=1;
                   }
                    $model = new Purchaseorder();
                    $model->purchase_order = $model::getLastNo();
                    $model->vendor_id = $vendorid;
                    $model->purchase_date = strtotime(date('d-m-Y'));
                    $model->status = 1;
                    $model->created_by = Yii::$app->user->identity->id;
                     if($model->save(false)){
                        if(count($cart)>0){
                            foreach($cart as $kay =>$value){
                                $modelline = new \backend\models\Purchaseorderline();
                                $modelline->purchase_order_id = $model->id;
                                $modelline->product_id = Product::getProdid($value['product_id']);
                                $modelline->qty = $value['qty'];
                                $modelline->price =$value['price'];
                                $modelline->line_amount=$value['qty'] * $value['price'];
                                $modelline->save(false);
                            }
                        }
                        $this->updateAmount($model->id);
                         unset($session['cart']);
                         return $this->redirect(['update', 'id' => $model->id]);
                }
              }
               
               return 0;
           }
                // $prodid = Yii::$app->request->post('product_id');
                // $qty = Yii::$app->request->post('qty');
                // $price = Yii::$app->request->post('price');
                // $lineamt = Yii::$app->request->post('line_amount');
    }
    public function actionUpdatecart(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('prodid');
            $prc = Yii::$app->request->post('prc');
            $qty = Yii::$app->request->post('qty');
            if($id){
                $model = Product::getProductInfo($id);
                $cart = new cart();
                $res = $cart->updateCart($id,$model->product_code,$model->name,$prc,$qty);
                return $res;
            }else{
                return 2;
            }
        }
    }
    public function actionRemoveitemcart(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('prodid');
            if($id){
                 $cart = new cart();
                 $res = $cart->removeItemCart($id);
                // return $res;
                 $session = Yii::$app->session;
                if(isset($session['cart'])){
                   return count($session['cart']);
                }
            }else{
                return 2;
            }
        }
    }
    public function actionPrintpo(){
        if(Yii::$app->request->isAjax){
            $id = Yii::$app->request->post('id');
            if($id){
                $session = Yii::$app->session;
                $session['printpo'] = $id;
                return $this->redirect(['displayprint']);
            }
        }
    }
    public function actionDisplayprint(){
        $session = Yii::$app->session;
        if(isset($session['printpo'])){
            $id = $session['printpo'];
                $model = Purchaseorder::find()->where(['id'=>$id])->one();
                if($model){
                    $modelline = \backend\models\Purchaseorderline::find()->where(['purchase_order_id'=>$model->id])->all();
                       $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                        'format' => Pdf::FORMAT_A4, 
                        'orientation' => Pdf::ORIENT_PORTRAIT, //PORTRAIT , ORIENT_LANDSCAPE
                        'destination' => Pdf::DEST_BROWSER, 
                        'content' => $this->renderPartial('_print',[
                            'model'=>$model,  
                            'modelline'=>$modelline,  
                            ]),
                        //'content' => "nira",
                        'cssFile' => '@backend/web/css/pdf.css',
                        'options' => [
                            'title' => 'ใบสั่งซื้อ',
                            'subject' => ''
                        ],
                        'methods' => [
                           // 'SetHeader' => ['ใบสั่งซื้อ||Generated On: ' . date("r")],
                           // 'SetFooter' => ['|Page {PAGENO}|'],
                        ]
                    ]);
                     return $pdf->render();
        }
        }
    }
}
