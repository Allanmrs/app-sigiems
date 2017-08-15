<?php

namespace app\modules\soporte\controllers;

use Yii;
use app\modules\soporte\models\BajasDictamen;
use app\modules\soporte\models\BajasDictamenSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\helpers\Html; 
use yii\web\NotFoundHttpException;

use yii\db\Expression;
use app\modules\soporte\models\InvBajas;
use app\modules\admin\models\CatTipoEquipo;

/**
 * BajasDictamenController implements the CRUD actions for BajasDictamen model.
 */
class BajasDictamenController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
               // 'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index','create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all BajasDictamen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BajasDictamenSearch();
        $searchModel2 = new BajasDictamenSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider2 = $searchModel2->search2(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'searchModel2' => $searchModel2, 
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
        ]);
    }

    /**
     * Displays a single BajasDictamen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel2 = new BajasDictamenSearch();
        $dataProvider2 = $searchModel2->search2(Yii::$app->request->queryParams);
        return $this->render('view', [
            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BajasDictamen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idb)
    {
        $model = new BajasDictamen();
         $model2 = InvBajas::findOne($idb);
        if ($model->load(Yii::$app->request->post()) ) {
             $model->bloq=0;
             $model->id_baja = $idb;
             $model->clabe_cams = 1;
             $model->created_by=Yii::$app->user->identity->user_id;
             $model->created_at = $fecha = date("Y-m-d");//new Expressi

             /*$model->tipoEquipo->nombre;*/
//$plantel = @Yii::$app->user->identity->id_plantel;
//$count = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM inv_equipos WHERE inv_equipos.estado=1 AND inv_equipos.id_plantel=$plantel")->queryScalar();
                    

           if (!$model->save()) {
                echo "<pre>";
                print_r($model->getErrors());
                exit;
                //Yii::$app->session->setflash("error","Error: Progresivo No existe en el sistema inventarial y/o progresivo ya fue registrado ");
                 //return $this->redirect(['create']);
                //exit;
                # code...
            }
            return $this->redirect(['/soporte/inv-bajas/periodo', 'idp' => $idp]);
        }else {
            return $this->render('create', [
                'model' => $model,
                'idb' => $idb,
                'model2' => $model2,
            ]);
        }
    }

    /**
     * Updates an existing BajasDictamen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$idb,$idp)
    {
        $model = $this->findModel($id);
        $model2 = InvBajas::findOne($idb);

        if ($model->load(Yii::$app->request->post()) ) {
        

        $model->updated_by=@Yii::$app->user->identity->user_id;
        $model->updated_at = new Expression('NOW()');    
       if (!$model->save()) {
                echo "<pre>";
                print_r($model->getErrors());
                exit;
            }
            return $this->redirect(['/soporte/inv-bajas/periodo', 'idp' => $idp]);
         

        } else {
            return $this->render('update', [
                'model' => $model,
                'idb' => $idb,
                'model2' => $model2,

            ]);
        }
    }

    /**
     * Deletes an existing BajasDictamen model.
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
     * Finds the BajasDictamen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BajasDictamen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BajasDictamen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}