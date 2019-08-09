<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Ordenproduccion;
use app\models\Ordenproducciondetalle;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;



?>
    
    
    <div class="modal-body">
                         
        
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-5 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                'options' => []
            ],
        ]); ?>
        <div class="table table-responsive">
            <div class="panel panel-success ">
                
                <div class="panel-body">
                    <?= $model->mensaje ?>
                </div>
                <div class="panel-footer text-right">                    
                    <?= Html::a('<span class="glyphicon glyphicon-print"></span> Exportar', ['imprimir', 'id' => $model->id_reporte], ['class' => 'btn btn-success']);?>
                </div>

            </div>            
        </div>
        
    </div>
<?php ActiveForm::end(); ?>