<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Proceso;

/**
 * ContactForm is the model behind the contact form.
 */
class FormProceso extends Model
{
    public $id_proceso;
    public $proceso;

    public function rules()
    {
        return [

            ['id_proceso', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan númerossss'],
            ['proceso', 'proceso_existe'],
            ['proceso', 'required', 'message' => 'Campo requerido'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id_proceso' => '',
            'proceso' => 'Proceso:',

        ];
    }

    public function proceso_existe($attribute, $params)
    {
        //Buscar el proceso en la tabla
        $table = Proceso::find()->where("proceso=:proceso", [":proceso" => $this->proceso])->andWhere("id_proceso!=:id_proceso", [':id_proceso' => $this->id_proceso]);
        //Si el proceso existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El proceso ya existe ".$this->proceso);
        }
    }
}
