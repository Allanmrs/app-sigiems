<?php

namespace app\modules\soporte\controllers;

use Yii;
use app\modules\soporte\models\InvImpresoras;
use app\modules\soporte\models\InvImpresorasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\soporte\models\CatMarcaimp;
use app\modules\soporte\models\CatModeloimp;

/**
 * InvImpresorasController implements the CRUD actions for InvImpresoras model.
 */
class InvImpresorasController extends Controller
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
     * Lists all InvImpresoras models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvImpresorasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionModelos($id)
    {
        $cuentaModelos = CatModeloimp::find()->where(['id_marca'=>$id])->count();
        $modelos = CatModeloimp::find()->where(['id_marca'=>$id])->all();

        if ($cuentaModelos > 0) {
            foreach ($modelos as $key => $value) {
                echo "<option value=". $value->id . ">". $value->modelo. "</option>";
            }
        }else{
            echo "<option>-</option>";
        }
    }

    /**
     * Displays a single InvImpresoras model.
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
     * Creates a new InvImpresoras model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvImpresoras();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InvImpresoras model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InvImpresoras model.
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
     * Finds the InvImpresoras model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvImpresoras the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvImpresoras::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}