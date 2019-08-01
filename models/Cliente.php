<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id_cliente
 * @property string $nombre_id
 * @property string $nombres
 * @property string $apellidos
 * @property string $nombre_completo
 * @property string $compania
 * @property string $telefono_1
 * @property string $telefono_2
 * @property string $email
 * @property string $pagina
 * @property string $direccion_1
 * @property string $direccion_2
 * @property string $ciudad
 * @property string $departamento
 * @property string $pais
 * @property string $placa
 *
 * @property Reporte[] $reportes
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente','nombre_id', 'nombres', 'apellidos', 'nombre_completo', 'compania', 'email', 'pagina', 'direccion_1', 'direccion_2', 'ciudad', 'departamento', 'pais'], 'string', 'max' => 50],
            [['telefono_1', 'telefono_2'], 'string', 'max' => 30],
            [['placa'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Id Cliente',
            'nombre_id' => 'Nombre ID',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'nombre_completo' => 'Nombre Completo',
            'compania' => 'Compania',
            'telefono_1' => 'Telefono 1',
            'telefono_2' => 'Telefono 2',
            'email' => 'Email',
            'pagina' => 'Pagina',
            'direccion_1' => 'Direccion 1',
            'direccion_2' => 'Direccion 2',
            'ciudad' => 'Ciudad',
            'departamento' => 'Departamento',
            'pais' => 'Pais',
            'placa' => 'Placa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReportes()
    {
        return $this->hasMany(Reporte::className(), ['id_cliente' => 'id_cliente']);
    }
}
