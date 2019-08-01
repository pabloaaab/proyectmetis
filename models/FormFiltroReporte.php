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
    public $fecha_enviado;

    public function rules()
    {
        return [
            ['id_proceso', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['placa', 'default'],            
            ['fecha_enviado', 'safe'],                       
        ];
    }

    public function attributeLabels()
    {
        return [
            'fecha_enviado' => 'Fecha Proceso:',   
            'id_proceso' => 'Proceso:',   
            'placa' => 'Placa:',   
        ];
    }
}