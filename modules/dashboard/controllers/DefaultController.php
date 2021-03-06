<?php

namespace app\modules\dashboard\controllers;

use yii\web\Controller;

/**
 * Default controller for the `dashboard` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPrestamos()
    {
        return $this->render('prestamos');
    }
    public function actionPrestaequipos()
    {
        return $this->render('prestaequipos');
    }

     public function actionEventos()
    {
        return $this->render('eventos');
    }

}
