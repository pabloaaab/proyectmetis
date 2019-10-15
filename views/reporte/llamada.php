<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
                    <a align="right" href="<?= Url::toRoute(["reporte/descargarllamada", "id" => $model->mensaje]) ?>" class="link"><?= $model->mensaje ?></a>
                </div>
                <div class="panel-footer text-right">                    
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Cerrar</button>                    
                </div>

            </div>            
        </div>
        
    </div>
<?php ActiveForm::end(); ?>