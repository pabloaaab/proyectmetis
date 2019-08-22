<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroReporte extends Model
{
    public $id_proceso;
    public $placa;
    public $fecha_enviado_desde;
    public $fecha_enviado_hasta;

    public function rules()
    {
        return [
            ['id_proceso', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['placa', 'default'],            
            [['fecha_enviado_desde','fecha_enviado_hasta'], 'safe'],                       
        ];
    }

    public function attributeLabels()
    {
        return [
            'fecha_enviado_desde' => 'Fecha Proceso Desde:',   
            'fecha_enviado_hasta' => 'Fecha Proceso Hasta:',   
            'id_proceso' => 'Proceso:',   
            'placa' => 'Placa:',   
        ];
    }
}