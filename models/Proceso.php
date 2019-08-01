<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proceso".
 *
 * @property int $id_proceso
 * @property string $proceso
 *
 * @property Reporte[] $reportes
 */
class Proceso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'proceso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['proceso'], 'required'],
            [['proceso'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_proceso' => 'Id Proceso',
            'proceso' => 'Proceso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reporte::className(), ['id_proceso' => 'id_proceso']);
    }
}
