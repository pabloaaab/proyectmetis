<?php

/* @var $this yii\web\View */

$this->title = 'Metis';
?>
<div class="site-index">
    
    <div class="jumbotron">
        <h1>Registros de contactos a cliente</h1>
        <img src="images/logowillis.jfif" align="center" width="25%" >        
        <img src="images/logometis.png" align="center" width="25%" >                
    </div>
<?php $remoteURL = 'http://192.168.0.13/grabaciones/salida/2019-08-30/MEDELLIN13-1567202214.1656.WAV';

// Force download
header("Content-type: audio/wav"); 
header("Content-Disposition: attachment; filename=".basename($remoteURL));
ob_end_clean();
readfile($remoteURL);
?>
    <div class="body-content">

        <div class="row">
            <div class="col-lg-16">

            </div>


        </div>

    </div>
</div>
