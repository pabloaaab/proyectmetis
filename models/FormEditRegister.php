<?php

namespace app\models;
use Yii;
use yii\base\model;
use app\models\Users;

class FormEditRegister extends model{

    public $codusuario;
    public $username;
    public $nombrecompleto;
    public $role;    
    public $email;    
    public $activo;
    public $fechacreacion;    

    public function rules()
    {
        return [
            [['username', 'email', 'nombrecompleto','role','activo'], 'required', 'message' => 'Campo requerido'],
            ['username', 'match', 'pattern' => "/^.{3,50}$/", 'message' => 'Mínimo 3 y máximo 30 caracteres'],
            ['username', 'match', 'pattern' => "/^[0-9a-z]+$/i", 'message' => 'Sólo se aceptan letras y números'],
            ['username', 'usuario_existe'],
            
            ['nombrecompleto', 'string'],
            ['fechacreacion', 'safe'],
            ['email', 'match', 'pattern' => "/^.{5,80}$/", 'message' => 'Mínimo 5 y máximo 80 caracteres'],
            ['email', 'email', 'message' => 'Formato no válido'],
            ['email', 'email_existe'],                        
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Identificacion:',
            'nombrecompleto' => 'Nombre:',
            'role' => 'Tipo Usuario:',            
            'email' => 'Email:',
            'activo' => 'Estado:',
            'fechacreacion' => 'Fecha Creacion:',
        ];
    }

    public function email_existe($attribute, $params)
    {

        //Buscar el email en la tabla
        //$table = Users::find()->where("emailusuario=:emailusuario", [":emailusuario" => $this->emailusuario]);
        $table = Users::find()->where("email=:email", [":email" => $this->email])->andWhere("username!=:username", [':username' => $this->username]);
        //Si el email existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El email seleccionado existe");
        }
    }

    public function usuario_existe($attribute, $params)
    {
        //Buscar el usuario en la tabla
        //$table = Users::find()->where("username=:username", [":username" => $this->username]);
        $table = Users::find()->where("username=:username", [":username" => $this->username])->andWhere("fechacreacion!=:fechacreacion", [':fechacreacion' => $this->fechacreacion]);
        //Si el username existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El usuario seleccionado existe");
        }
    }

}