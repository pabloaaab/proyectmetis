<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroCliente
extends Model
{
    public $placa;
    public $email;
    public $nombres;
    public $apellidos;

    public function rules()
    {
        return [

            ['placa', 'default'],
            ['email', 'default'],
            ['nombres', 'default'],
            ['apellidos', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'placa' => 'Placa:',
            'email' => 'Email:',
            'nombres' => 'Nombres:',
            'apellidos' => 'Apellidos:',
        ];
    }
}
