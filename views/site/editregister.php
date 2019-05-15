<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Editar Usuario';

?>

<?php
$form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]);
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        Información Usuario
    </div>
    <div class="panel-body">        														   		
        <div class="row">
            <?= $form->field($model, "username")->input("text") ?>                
        </div>        
        <div class="row">            
            <?= $form->field($model, 'role')->dropdownList(['1' => 'USUARIO', '2' => 'ADMINISTRADOR'], ['prompt' => 'Seleccione...']) ?>
        </div>
        <div class="row">
            <?= $form->field($model, "email")->input("email") ?>           
        </div>
        <div class="row">            
            <?= $form->field($model, "nombrecompleto")->input("text") ?>               
        </div>        
        <div class="row">            
            <?= $form->field($model, 'activo')->dropdownList(['1' => 'ACTIVO', '0' => 'INACTIVO'], ['prompt' => 'Seleccione el estado del usuario']) ?>
        </div>
        <div class="panel-footer text-right">            
            <a href="<?= Url::toRoute("site/usuarios") ?>" class="btn btn-primary"><span class='glyphicon glyphicon-circle-arrow-left'></span> Regresar</a>    
            <?= Html::submitButton("<span class='glyphicon glyphicon-floppy-disk'></span> Guardar", ["class" => "btn btn-success",]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>    