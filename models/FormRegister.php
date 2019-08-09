<?php

namespace app\models;
use Yii;
use yii\base\model;
use app\models\Users;

class FormRegister extends model{

    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $nombrecompleto;    
    public $perfil;
    
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_repeat','nombrecompleto','perfil'], 'required', 'message' => 'Campo requerido'],
            ['username', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'Mínimo 3 y máximo 50 caracteres'],
            ['username', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['username', 'username_existe'],
            ['nombrecompleto', 'match', 'pattern' => "/^.{1,50}$/", 'message' => 'Mínimo 3 y máximo 50 caracteres'],
            ['email', 'match', 'pattern' => "/^.{1,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['email', 'email', 'message' => 'Formato no válido'],
            ['email', 'email_existe'],
            ['password', 'match', 'pattern' => "/^.{2,16}$/", 'message' => 'Mínimo 6 y máximo 16 caracteres'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Los passwords no coinciden'],
        ];
    }

    public function email_existe($attribute, $params)
    {
        //Buscar el proceso en la tabla
        $table = Users::find()->where("email=:email", [":email" => $this->email])->andWhere("username!=:username", [':username' => $this->username]);
        //Si el proceso existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El email ya existe ".$this->email);
        }
    }

    public function username_existe($attribute, $params)
    {
        //Buscar el username en la tabla
        $table = Users::find()->where("username=:username", [":username" => $this->username]);

        //Si el username existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El usuario seleccionado existe");
        }
    }
    
    public function attributeLabels()
    {
        return [
            'username' => 'Usuario:',
            'email' => 'Email:',
            'password' => 'Clave:',
            'password_repeat' => 'Confirmar Clave:',            
            'nombrecompleto' => 'Nombre Completo:',
            'perfil' => 'Perfil:',
        ];
    }

}