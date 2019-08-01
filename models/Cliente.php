<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id_cliente
 *
 * @property Reporte[] $reportes
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Id Cliente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reporte::className(), ['id_cliente' => 'id_cliente']);
    }
}
