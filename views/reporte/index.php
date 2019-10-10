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

$this->title = 'Registros Contactos';
?>

<h1>Registros Contactos</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("reporte/index"),
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
$proceso = ArrayHelper::map(\app\models\Proceso::find()->all(), 'id_proceso','proceso');
?>    
    
<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $formulario->field($form, "placa")->input("search") ?>                       
            <?= $formulario->field($form, 'id_proceso')->dropDownList($proceso,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form,'fecha_enviado_desde')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>  
            <?= $formulario->field($form,'fecha_enviado_hasta')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>  
        </div>        
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("reporte/index") ?>" class="btn btn-primary">Limpiar</a>
        </div>
    </div>
</div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?> </div>
<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">Id</th>                                
                <th scope="col">Cliente</th>                                                
                <th scope="col">Proceso</th>
                <th scope="col">Fecha Proceso</th>
                <th scope="col">Tema</th>                
                <th scope="col">Email / Llamada / Sms</th>
                <th scope="col">Placa</th>
                <th scope="col">Mensaje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $val): ?>
                <tr>                    
                    <th scope="row"><?= $val->id_reporte ?></th>                
                    <td><?= $val->cliente->nombre_completo ?></td>                                                                    
                    <td><?= $val->proceso->proceso ?></td>
                    <td><?= $val->fecha_enviado ?></td>
                    <td><?= $val->tema ?></td>                    
                    <?php if ($val->id_proceso == 1){ ?>
                        <td><?= $val->email_enviado ?></td>
                    <?php } ?>
                    <?php if ($val->id_proceso == 2){ ?>
                        <td><?= $val->llamada_enviado ?></td>
                    <?php } ?>
                    <?php if ($val->id_proceso == 3){ ?>
                        <td><?= $val->sms_enviado ?></td>
                    <?php } ?>    
                    <td><?= $val->placa ?></td>
                    <td align="center">
                        <?php if ($val->id_proceso == 1){ // Carta ?>
                            <?php echo Html::a('<span class="glyphicon glyphicon-eye-open" style="font-size:25px;"></span>',
                                ['/reporte/carta','id' => $val->id_reporte],
                                [
                                    'title' => 'Carta',
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#carta'.$val->id_reporte,
                                ]
                            );                            
                            ?>                        
                            <div class="modal remote fade" id="carta<?= $val->id_reporte ?>">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($val->id_proceso == 2){ // Llamada ?>
                            <?php echo Html::a('<span class="glyphicon glyphicon-eye-open" style="font-size:25px;"></span>',
                                ['/reporte/llamada','id' => $val->id_reporte],
                                [
                                    'title' => 'Llamada',
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#llamada'.$val->id_reporte,
                                ]
                            );
                            ?>
                            <div class="modal remote fade" id="llamada<?= $val->id_reporte ?>">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($val->id_proceso == 3){ // Sms ?>
                            <?php echo Html::a('<span class="glyphicon glyphicon-eye-open" style="font-size:25px;"></span>',
                                ['/reporte/sms','id' => $val->id_reporte],
                                [
                                    'title' => 'Sms',
                                    'data-toggle'=>'modal',
                                    'data-target'=>'#sms'.$val->id_reporte,
                                ]
                            );
                            ?>
                            <div class="modal remote fade" id="sms<?= $val->id_reporte ?>">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content"></div>
                                </div>
                            </div>
                        <?php } ?>
                    </td>
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
