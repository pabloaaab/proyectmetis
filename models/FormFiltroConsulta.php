<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroConsulta extends Model
{
    public $fecha;    

    public function rules()
    {
        return [
            [['fecha'], 'required', 'message' => 'Campo requerido'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'fecha' => 'Fecha:',            
        ];
    }
}