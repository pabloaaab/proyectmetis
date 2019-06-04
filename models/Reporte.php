<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reporte".
 *
 * @property int $id
 * @property int $inc_recibida_p_d_a
 * @property int $inc_recibida
 * @property int $inc_recibida_g
 * @property int $inc_recibida_p_f_d
 * @property int $inc_dev_p_d_a
 * @property int $inc_dev_recibida
 * @property int $inc_dev_g
 * @property int $inc_dev_p_f_d
 * @property int $rep_inc_p_d_a
 * @property int $rep_inc_recibida
 * @property int $rep_inc_g
 * @property int $rep_inc_p_f_d
 * @property int $rep_inc_dev_p_d_a
 * @property int $rep_inc_dev_recibida
 * @property int $rep_inc_dev_g
 * @property int $rep_inc_dev_p_f_d
 * @property string $fecha_gestion
 */
class Reporte extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reporte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inc_recibida_p_d_a', 'inc_recibida', 'inc_recibida_g', 'inc_recibida_p_f_d', 'inc_dev_p_d_a', 'inc_dev_recibida', 'inc_dev_g', 'inc_dev_p_f_d', 'rep_inc_p_d_a', 'rep_inc_recibida', 'rep_inc_g', 'rep_inc_p_f_d', 'rep_inc_dev_p_d_a', 'rep_inc_dev_recibida', 'rep_inc_dev_g', 'rep_inc_dev_p_f_d'], 'integer'],
            [['fecha_gestion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inc_recibida_p_d_a' => 'Inc Recibida P D A',
            'inc_recibida' => 'Inc Recibida',
            'inc_recibida_g' => 'Inc Recibida G',
            'inc_recibida_p_f_d' => 'Inc Recibida P F D',
            'inc_dev_p_d_a' => 'Inc Dev P D A',
            'inc_dev_recibida' => 'Inc Dev Recibida',
            'inc_dev_g' => 'Inc Dev G',
            'inc_dev_p_f_d' => 'Inc Dev P F D',
            'rep_inc_p_d_a' => 'Rep Inc P D A',
            'rep_inc_recibida' => 'Rep Inc Recibida',
            'rep_inc_g' => 'Rep Inc G',
            'rep_inc_p_f_d' => 'Rep Inc P F D',
            'rep_inc_dev_p_d_a' => 'Rep Inc Dev P D A',
            'rep_inc_dev_recibida' => 'Rep Inc Dev Recibida',
            'rep_inc_dev_g' => 'Rep Inc Dev G',
            'rep_inc_dev_p_f_d' => 'Rep Inc Dev P F D',
            'fecha_gestion' => 'Fecha Gestion',
        ];
    }
}
