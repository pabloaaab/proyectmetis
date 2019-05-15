<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;

$this->title = 'Informe Pagos';
?>

<h1>Informe Pagos</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("informepagos/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<?php
$sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','estado',1])->all(), 'sede','sede');
$nivel = ArrayHelper::map(\app\models\Nivel::find()->all(), 'nivel','nivel');
?>    
    
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>                       
            <?= $formulario->field($form, 'sede')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form,'fechapago')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>     
            <?= $formulario->field($form, 'tipo_pago')->dropDownList(['mensualidad' => 'Mensualidad','otros' => 'Otros Pagos'],['prompt' => 'Seleccione...' ]) ?>                        
            <?= $formulario->field($form, "nro_pago")->input("search") ?>
        </div>
        <div class="row">
              <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Dia','value' => "dia", 'uncheck' => null]) ?>
            <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Mes','value' => "mes", 'uncheck' => null]) ?>
            <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Anio','value' => "anio", 'uncheck' => null]) ?>
            </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("informepagos/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    


<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Informe</h4>
      </div>
      <div class="modal-body">          
          <p class="alert-info">Total otros pagos: <?= '$ '.number_format($result[0]['otrospagos']) ?></p>
        <p class="alert-danger">Total otros pagos Anulados: <?= '$ '.number_format($result[0]['otrospagosanulados']) ?></p>        
        <p class="alert-info">Total pagos sede Medellin: <?= '$ '.number_format($result[0]['pagosmedellin']) ?></p>
        <p class="alert-danger">Total pagos sede Medellin Anulados: <?= '$ '.number_format($result[0]['pagosmedellinanulado']) ?></p>
        <p class="alert-info">Total pagos sede Rionegro: <?= '$ '.number_format($result[0]['pagosrionegro']) ?></p>
        <p class="alert-danger">Total pagos sede Rionegro Anulados: <?= '$ '.number_format($result[0]['pagosrionegroanulado']) ?></p>
        <p class="alert-info">Subtotal: <?= '$ '.number_format($subtotal) ?></p>
        <p class="alert-danger">Total Anulados: <?= '$ '.number_format($totalanulado) ?></p>
        <p class="alert-info"><b>Total General: <?= '$ '.number_format($grantotal) ?></b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?> <a class="btn btn-info" data-toggle="modal" data-target="#myModal">Ver informe</a></div>
<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N° Pago</th>                                
                <th scope="col">Estudiante</th>                                                
                <th scope="col">Pago</th>
                <th scope="col">Tipo Pago</th>                
                <th scope="col">Valor Pago</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Sede</th>
                <th scope="col">Nivel</th>                
                <th scope="col">Observaciones</th>
                <th scope="col">Anulado</th>                                               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $val): ?>
                <tr>
                    <?php if ($val->anulado == "") {
                        $anulado = "NO";
                    } else {
                        $anulado = "SI";
                    } ?>
                    <?php if ($val->sede == "") {
                        $sede = "sin definir";
                    } else {
                        $sede = $val->sede;
                    } ?>
                    <th scope="row"><?= $val->nropago ?></th>                
                    <td><?= $val->entificacion->nombreestudiante ?></td>                                                                    
                    <td><?= $val->mensualidad ?></td>
                    <td><?= $val->ttpago ?></td>                    
                    <td><?= number_format($val->total) ?></td>
                    <td><?= $val->fecha_registro ?></td>
                    <td><?= $sede ?></td>
                    <td><?= $val->nivel ?></td>                    
                    <td><?= $val->observaciones ?></td>
                    <td align="center"><?= $anulado ?></td>                        
                </tr>
            </tbody>
<?php endforeach; ?>
    </table>    
    
    <div class = "form-group" align="left">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>    
</div>

<?php $formulario->end() ?>

<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel-footer text-right">
    <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> excel", ["class" => "btn btn-primary", 'name' => 'excel', 'value' => 1]) ?>        
</div>

<?php $formulario->end() ?>