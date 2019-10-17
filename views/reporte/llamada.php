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
                    
<a href="download/MEDELLIN13-1567202214.1656 (3).WAV" download="Acme Documentation (ver. 2.0.1).WAV">Download Text</a>

                </div>
                </div>
                <div class="panel-footer text-right">                    
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Cerrar</button>                    
                </div>

            </div>            
        </div>
        
    </div>
<?php ActiveForm::end(); ?>
<?php    
    function downloadFile($dir, $file, $extensions=[])
    {
        //Si el directorio existe
        if (is_dir($dir))
        {
            //Ruta absoluta del archivo
            $path = $dir.$file;

            //Si el archivo existe
            if (is_file($path))
            {
                //Obtener información del archivo
                $file_info = pathinfo($path);
                //Obtener la extensión del archivo
                $extension = $file_info["extension"];

                if (is_array($extensions))
                {
                    //Si el argumento $extensions es un array
                    //Comprobar las extensiones permitidas
                    foreach($extensions as $e)
                    {
                        //Si la extension es correcta
                        if ($e === $extension)
                        {
                            //Procedemos a descargar el archivo
                            // Definir headers
                            $size = filesize($path);
                            header("Content-Type: application/force-download");
                            header("Content-Disposition: attachment; filename=$file");
                            header("Content-Transfer-Encoding: binary");
                            header("Content-Length: " . $size);
                            // Descargar archivo
                            readfile($path);
                            //Correcto
                            return true;
                        }
                    }
                }
            }
        }
        //Ha ocurrido un error al descargar el archivo
        return false;
    }
    ?>