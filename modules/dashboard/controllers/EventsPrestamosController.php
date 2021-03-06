<?php
/*****************************************************************************************
 * EduSec  Open Source Edition is a School / College management system developed by
 * RUDRA SOFTECH. Copyright (C) 2010-2015 RUDRA SOFTECH.

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses. 

 * You can contact RUDRA SOFTECH, 1st floor Geeta Ceramics, 
 * Opp. Thakkarnagar BRTS station, Ahmedbad - 382350, India or
 * at email address info@rudrasoftech.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * RUDRA SOFTECH" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by RUDRA SOFTECH".
 *****************************************************************************************/
/**
 * EventsController implements the CRUD actions for Events model.
 *
 * @package EduSec.modules.dashboard.controllers
 */

namespace app\modules\dashboard\controllers;

use Yii;
use app\modules\dashboard\models\EventsPrestamos;
use app\modules\dashboard\models\EventsPrestamosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\web\UploadedFile;

class EventsPrestamosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsPrestamosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Events model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

      public function actionEventos($id)
    {
        return $this->render('eventos');
    }

    public function actionPrestamosEquipos($id)
    {
        return $this->render('prestaequipos');
    }

       public function actionDocto($event_id)
    {
        $model = $this->findModel($event_id);

        if ($model->load(Yii::$app->request->post()) || isset($_POST['EventsPrestamos'])) {

        $model->file = UploadedFile::getInstance($model,'file');
        $model->file->saveAs('pdfprestamos/'.$model->file->baseName.'-'.date('Y-m-d h:m:s').'.'.$model->file->extension);
       //  $model->file->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
        $model->documento = $model->file->baseName.'-'.date('Y-m-d h:m:s').'.'.$model->file->extension;
       // $model->docto=1;
        $model->updated_by=@Yii::$app->user->identity->user_id;
        $model->updated_at = new Expression('NOW()');

        if($model->save()) {
                if(isset($_GET['return_dashboard']))
                    return $this->redirect(['/dashboard/events-prestamos']);
            else 
            return $this->redirect(['index']);
        }
        else {
                    return $this->render('_form2', ['model' => $model,]);
            }
        } else {
            return $this->render('_form2', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAddEvent()
    {
        $model = new EventsPrestamos();

        if ($model->load(Yii::$app->request->post()) || isset($_POST['EventsPrestamos'])) {

		/*$eventList = Events::find()->where(['LIKE', 'event_start_date', Yii::$app->dateformatter->getDateFormat($_POST['Events']['event_start_date'])])->andwhere(['is_status'=> 0])->count();

		if($eventList > 6) {
			Yii::$app->session->setFlash('maxEvent', "<b><i class='fa fa-warning'></i> Maximum Events Limit Reached, you can not add more event for this day</b>");
			return $this->redirect(['index']);
		}*/
		$model->attributes = $_POST['EventsPrestamos'];
		$model->event_start_date = Yii::$app->dateformatter->storeDateTimeFormat($_POST['EventsPrestamos']['event_start_date']);
		$model->event_end_date = Yii::$app->dateformatter->storeDateTimeFormat($_POST['EventsPrestamos']['event_end_date']);
        $model->event_title='Prestamo de Equipo';
        $model->event_detail='El Tiempo de Prestamo se Define en el Formato';
        $model->event_all_day = 0;
        $model->is_status=0;
        $model->folio= $model->event_id;
		$model->created_by = Yii::$app->user->identity->user_id; //Yii::$app->getid->getId();
		$model->created_at = new \yii\db\Expression('NOW()');

        if (!$model->save()) {

                
                echo "<pre>";
                print_r($model->getErrors());
                exit;
               // Yii::$app->session->setflash("error","Error: Progresivo No existe en el sistema inventarial y/o progresivo ya fue registrado ");
                 //return $this->redirect(['create']);
                //exit;
                # code...
            }else{
                Yii::$app->telegram->sendMessage([
                'chat_id' => -224731334,
                'text' => "Prestamo de Equipo hora inicial:$model->event_start_date fecha_final:$model->event_end_date",
                ]);
            }

		if($model->save()) {
		    if(isset($_GET['return_dashboard']))
	            	return $this->redirect(['/dashboard']);
		    else 
			return $this->redirect(['index']);
		}
		else {
                    return $this->renderAjax('_form', ['model' => $model,]);
        	}
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    public function actionViewEvents($start=NULL,$end=NULL,$_=NULL) {

	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

	    $eventList = EventsPrestamos::find()->where(['is_status'=> 0])->all();

	    $events = [];

	    foreach ($eventList as $event) {
	      $Event = new \yii2fullcalendar\models\Event();
	      $Event->id = $event->event_id;
	      $Event->title = $event->event_title;
	      $Event->description = $event->event_detail;
	      $Event->start = $event->event_start_date;
	      $Event->end = $event->event_end_date;
	      $Event->color = (($event->event_type == 1) ? '#00C0EF' : (($event->event_type == 2) ? '#F39C12' : (($event->event_type == 3) ? '#00A65A' : '#ff0000')));
	      $Event->textColor = '#FFF';
	      $Event->borderColor = '#000';
	      $Event->event_type = (($event->event_type == 1) ? 'Pendiente' : (($event->event_type == 2) ? 'En Proceso' : (($event->event_type == 3) ? 'Termiando' : 'Fuera de Tiempo')));
	      $Event->allDay = ($event->event_all_day == 1) ? true : false;
	     // $Event->url = $event->event_url;
	      $events[] = $Event;
	    }
	    return $events;
    }


    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateEvent($event_id)
    {
        $model = $this->findModel($event_id);

        if ($model->load(Yii::$app->request->post()) || isset($_POST['EventsPrestamos'])) {

		$model->attributes = $_POST['EventsPrestamos'];
		$model->event_start_date = Yii::$app->dateformatter->storeDateTimeFormat($_POST['EventsPrestamos']['event_start_date']);
		$model->event_end_date = Yii::$app->dateformatter->storeDateTimeFormat($_POST['EventsPrestamos']['event_end_date']);
		$model->updated_by = Yii::$app->user->identity->user_id;
		$model->updated_at = new \yii\db\Expression('NOW()');

		if($model->save()) {
	            if(isset($_GET['return_dashboard']))
	            	return $this->redirect(['/dashboard/events-prestamos']);
		    else 
			return $this->redirect(['index']);
		}
		else {
                    return $this->renderAjax('_form', ['model' => $model,]);
        	}
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEventDelete($e_id)
    {
        $model = Events::findOne($e_id);
	$model->is_status = 2;
	$model->updated_by = 1;//Yii::$app->getid->getId();
	$model->updated_at = new \yii\db\Expression('NOW()');
	$model->save();

        if(isset($_GET['return_dashboard']))
		return $this->redirect(['/dashboard']);
	else 
		return $this->redirect(['index']);
    }

    /**
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EventsPrestamos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
