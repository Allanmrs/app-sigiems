<?php

namespace app\modules\soporte\models;

use Yii;

/**
 * This is the model class for table "cat_mserver".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $tipo
 */
class CatMserver extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_mserver';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'tipo' => 'Tipo',
        ];
    }
}
