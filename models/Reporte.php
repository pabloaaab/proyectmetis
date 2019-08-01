<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reporte".
 *
 * @property int $id_reporte
 * @property int $id_cliente
 * @property int $id_proceso
 *
 * @property Cliente $cliente
 * @property Proceso $proceso
 */
class Reporte extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reporte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'id_proceso'], 'integer'],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
            [['id_proceso'], 'exist', 'skipOnError' => true, 'targetClass' => Proceso::className(), 'targetAttribute' => ['id_proceso' => 'id_proceso']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_reporte' => 'Id Reporte',
            'id_cliente' => 'Id Cliente',
            'id_proceso' => 'Id Proceso',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id_cliente' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProceso()
    {
        return $this->hasOne(Proceso::className(), ['id_proceso' => 'id_proceso']);
    }
}
