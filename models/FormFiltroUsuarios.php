<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroUsuarios extends Model
{
    public $username;
    public $nombrecompleto;
    public $perfil;
    public $sede;

    public function rules()
    {
        return [

            ['username', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['nombrecompleto', 'match', 'pattern' => '/^[a-záéíóúñ0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['perfil', 'default'],
            ['sede', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Usuario:',
            'nombrecompleto' => 'Nombre Completo:',
            'perfil' => 'Perfil:',
            'sede' => 'Sede:',
        ];
    }
}
