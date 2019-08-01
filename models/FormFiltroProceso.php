<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroProceso extends Model
{
    public $buscar;

    public function rules()
    {
        return [

            ['buscar', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'buscar' => 'Buscar:',
        ];
    }
}
