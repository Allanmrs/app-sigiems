<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\soporte\models\TipoEquipo;
use app\modules\soporte\models\CatMarca;
use app\modules\soporte\models\CatModelo;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\soporte\models\InvEquiposSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inv Equipos';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<br>
<br>
<br>
<div class="inv-equipos-index">

  <div class="col-xs-12">
  <div class="col-lg-8 col-sm-8 col-xs-12 no-padding edusecArLangCss"><h3 class="box-title"><i class="fa fa-th-list"></i> 
  <?php echo $this->title ?></h3></div>
  <div class="col-lg-4 col-sm-4 col-xs-12 no-padding" style="padding-top: 20px !important;">
  <div class="col-xs-4 left-padding">

           
             <?= Html::a('Agregar Equipos', ['create'], ['class' => 'btn btn-block btn-success']) ?>

  </div>
  <div class="col-xs-4 left-padding">
 
      <?= Html::a(Yii::t('app', 'PDF'), ['/export-data/export-to-pdf', 'model'=>get_class($searchModel2)], ['class' => 'btn btn-block btn-warning', 'target'=>'_blank']) ?>

  </div>
  <div class="col-xs-4 left-padding">
 
      <?= Html::a(Yii::t('app', 'EXCEL'), ['/export-data/export-excel', 'model'=>get_class($searchModel2)], ['class' => 'btn btn-block btn-primary', 'target'=>'_blank']) ?>

  </div>
  </div>
</div>

 
</div>

<div class="col-xs-12" style="padding-top: 10px;">
  <div class="box">
   <div class="box-body table-responsive">
     <div class="assignment-index">
        <?php

    //echo  "clase:".get_class($searchModel);  
        
    Pjax::begin([
        'enablePushState'=>false,
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'id',
            'progresivo',
            //'id_tipo',
             [
              'attribute'=>'id_tipo',
              'value' => 'tipoEquipo.nombre',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\soporte\models\TipoEquipo::find()->where(['tipo'=>1])->orderBy('nombre')->asArray()->all(),'id','nombre')
            ],
             [
              'attribute'=>'marca',
              'value' => 'catMarca.nombre',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\soporte\models\CatMarca::find()->orderBy('nombre')->asArray()->all(),'id','nombre')
            ],
            [
              'attribute'=>'modelo',
              'value' => 'catModelo.modelo',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\soporte\models\CatModelo::find()->orderBy('modelo')->asArray()->all(),'id','modelo')
            ],
            'serie',
            [
              'attribute'=>'estado',
              'value' => 'estadoEquipo.nombre',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\soporte\models\EstadoEquipo::find()->orderBy('nombre')->asArray()->all(),'id','nombre')
            ],
           // 'procesador',
            'ram',
            'disco_duro',
                [
              'attribute'=>'id_area',
              'value' => 'catAreas.nombre',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\admin\models\CatAreas::find()->where(['id_plantel'=>Yii::$app->user->identity->id_plantel])->orderBy('nombre')->asArray()->all(),'id_area','nombre')
            ],

             [
              'attribute'=>'id_piso',
              'value' => 'catPisos.nombre',
              'filter' => yii\helpers\ArrayHelper::map(app\modules\admin\models\CatPisos::find()->orderBy('nombre')->asArray()->all(),'id','nombre')
            ],
            /*'id_plantel',
            'id_area',
            'id_piso',*/
            // 'observaciones',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

             [
             'class' => 'app\components\CustomActionColumn',
      'template' => '{view} {delete}',
      'buttons' => [
        'view' => function ($url, $model) {
                return (Html::a('<span class="glyphicon glyphicon-search"></span>', $url, ['title' => Yii::t('app', 'View'),]));
            },
        'delete' => function ($url, $model) {
                return ((Yii::$app->user->can("borrarEquipos")) ? Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, ['title' => Yii::t('app', 'Delete'), 'data' => ['confirm' => Yii::t('app', 'estas seguro de eliminar el Equipo?'),'method' => 'post'],]) : '');
            }
      ],
            ],
        ],
    ]);
    Pjax::end();
    ?>
     </div>
   </div>
  </div>
</div>
