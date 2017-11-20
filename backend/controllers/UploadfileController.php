<?php

namespace backend\controllers;

use Yii;
use backend\models\Position;
use backend\models\PositionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PositionController implements the CRUD actions for Position model.
 */
class UploadfileController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Position models.
     * @return mixed
     */
    public function actionIndex()
    {
        $upload_product = new \backend\models\Modelfile();
        $upload_vendor = new \backend\models\Modelfile();
        return $this->render('index',
            [
                'upload_product' => $upload_product,
                'upload_vendor' => $upload_vendor,
            ]
            );
    }

}
