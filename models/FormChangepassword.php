<?php

namespace app\models;
use Yii;
use yii\base\model;
use app\models\Users;

class FormChangepassword extends model{

    public $id;    
    public $password;
    public $authKey;
    public $accessToken;    
    public $password_repeat;

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required', 'message' => 'Campo requerido'],            
            ['password', 'match', 'pattern' => "/^.{2,16}$/", 'message' => 'Mínimo 2 y máximo 16 caracteres'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Los passwords no coinciden'],
        ];
    }

    public function attributeLabels()
    {
        return [            
            'password' => 'Nueva Clave:',
            'password_repeat' => 'Confirmar Clave:',
        ];
    }    

}