<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Usuarios';
?>

    <h1>Usuarios</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("site/usuarios"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrousuarios">
        <div class="row" >
            <?= $formulario->field($form, "username")->input("search") ?>
            <?= $formulario->field($form, "nombrecompleto")->input("search") ?>            
            <?= $formulario->field($form, "perfil")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("site/usuarios") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    

<?php $formulario->end() ?>
    
    <div class="container-fluid">
        <div class="col-lg-2">

        </div>
    </div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>                                
                <th scope="col">Usuario</th>
                <th scope="col">Nombre Completo</th>
                <th scope="col">Email</th>
                <th scope="col">Perfil</th>                
                <th scope="col">Fecha Creación</th>                
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->id ?></th>                
                <td><?= $val->username ?></td>                                
                <td><?= $val->nombrecompleto ?></td>
                <td><?= $val->email ?></td>
                <td><?= $val->perfil ?></td>                
                <td><?= $val->fechacreacion ?></td>
                <td><a href="<?= Url::toRoute(["site/editar", "id" => $val->id]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                                
                <td><?= Html::a('Cambio Clave', ["site/changepassword", "id" => $val->id], ['class' => 'btn btn-default']) ?></td>                                
                <td></td>                                
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("site/register") ?>" class="btn btn-primary">Nuevo Usuario</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

