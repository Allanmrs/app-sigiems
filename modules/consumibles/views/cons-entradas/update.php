<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\consumibles\models\ConsEntradas */

$this->title = 'Update Cons Entradas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cons Entradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cons-entradas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
