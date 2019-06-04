<?php

namespace app\controllers;

use app\models\FormFiltroConsulta;
use app\models\OstThreadEvent;
use app\models\OstTicket;
use app\models\Reporte;
use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;
use PHPExcel;



class ConsultasController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroConsulta();            
            $fecha = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $fecha = Html::encode($form->fecha);
                    $porciones = explode("-", $fecha);
                    $dia = $porciones[2];
                    $mes = $porciones[1];
                    $anio = $porciones[0];
                    $table = Reporte::find()                                                        
                            ->andFilterWhere(['like', 'fecha_gestion', $anio.'-'.$mes])                            
                            ->orderBy('id asc')
                            ->all();
                    $model = $table;                    
                    $nrodiasmes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
                    $this->actionExcel($nrodiasmes,$dia,$mes,$anio,$fecha);
                } else {                    
                    $form->getErrors();
                }                                
            }             
            return $this->render('index', [                                        
                        'form' => $form,                        
            ]);
        } else {            
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionExcel($nrodiasmes,$dia,$mes,$anio,$fecha) {                
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        //$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);        
        $objPHPExcel->getActiveSheet()->getStyle('1:300')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);                        
        //pendientes al iniciar el mes fila 1 inclusiones
        $fechainiciarp = $anio.'-'.$mes.'-'.'01';        
        $pend_iniciar_mes = Reporte::find()->where(['like','fecha_gestion',$fechainiciarp])->one();
        if ($pend_iniciar_mes){$totalp_i_m = $pend_iniciar_mes->inc_recibida_p_d_a;}else{$totalp_i_m = 0;}        
        //recibidas fila 1
        $fecha_t_r = $anio.'-'.$mes;
        $total_r = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r])->all();
        $t_r = 0;
        $tt = 0;
        foreach ($total_r as $val){
            $t_r = $val->inc_recibida;
            $tt = $tt + $t_r;
        }
        //gestionadas fila 1       
        $total_g = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r])->all();
        $t_g = 0;
        $tg = 0;
        foreach ($total_g as $val){
            $t_g = $val->inc_recibida_g;
            $tg = $tg + $t_g;
        }
        //pendiente al iniciar mes fila 2
        if ($pend_iniciar_mes){$totalp_dev_i_m = $pend_iniciar_mes->inc_dev_p_d_a;}else{$totalp_dev_i_m = 0;}
        //recibidas fila 2 devueltas
        $fecha_t_r2 = $anio.'-'.$mes;
        $total_r2 = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r2])->all();
        $t_r2 = 0;
        $tt2 = 0;
        foreach ($total_r2 as $val){
            $t_r2 = $val->inc_dev_recibida;
            $tt2 = $tt2 + $t_r2;
        }
        //pendientes finalizar el mes fila 1
        $tpfm = $totalp_i_m + $tt - $tg - $tt2;
        //pendiente al iniciar mes fila 3
        if ($pend_iniciar_mes){$totalp_r_i_m = $pend_iniciar_mes->rep_inc_p_d_a;}else{$totalp_r_i_m = 0;}
        //recibidas fila 3 reprocesos
        $fecha_t_r3 = $anio.'-'.$mes;
        $total_r3 = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r3])->all();
        $t_r3 = 0;
        $tt3 = 0;
        foreach ($total_r3 as $val){
            $t_r3 = $val->rep_inc_recibida;
            $tt3 = $tt3 + $t_r3;
        }
        //gestionadas fila 3      
        $total_g3 = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r])->all();
        $t_g3 = 0;
        $tg3 = 0;
        foreach ($total_g3 as $val){
            $t_g3 = $val->rep_inc_g;
            $tg3 = $tg3 + $t_g3;
        }
        //pendiente al iniciar mes fila 4
        if ($pend_iniciar_mes){$totalp_r_i_d_i_m = $pend_iniciar_mes->rep_inc_dev_p_d_a;}else{$totalp_r_i_d_i_m = 0;}
        //recibidas fila 4 reprocesos devueltas
        $fecha_t_r4 = $anio.'-'.$mes;
        $total_r4 = Reporte::find()->where(['like','fecha_gestion',$fecha_t_r4])->all();
        $t_r4 = 0;
        $tt4 = 0;
        foreach ($total_r4 as $val){
            $t_r4 = $val->rep_inc_dev_recibida;
            $tt4 = $tt4 + $t_r4;
        }
        //pendientes finalizar el mes fila 3
        $tpfm3 = $totalp_r_i_m + $tt3 - $tg3 - $tt4;
        $objPHPExcel->setActiveSheetIndex(0)           
                    ->setCellValue('A1', $fechainiciarp)
                    ->setCellValue('A2', 'CASUISTICAS')
                    ->setCellValue('B2', 'PENDIENTES AL INICIAR EL MES')
                    ->setCellValue('C2', 'RECIBIDAS')
                    ->setCellValue('D2', 'GESTIONADAS')
                    ->setCellValue('E2', 'PENDIENTES AL FINALIZAR EL MES')
                    ->setCellValue('A3', 'INCLUSIONES')
                    ->setCellValue('B3', $totalp_i_m)
                    ->setCellValue('C3', $tt)
                    ->setCellValue('D3', $tg)
                    ->setCellValue('E3', $tpfm)
                    ->setCellValue('A4', 'INCLUSIONES DEVUELTAS')
                    ->setCellValue('B4', $totalp_dev_i_m)
                    ->setCellValue('C4', $tt2)
                    ->setCellValue('D4', 'N/A')
                    ->setCellValue('E4', 'N/A')
                    ->setCellValue('A5', 'REPROCESOS DE INCLUSIONES')
                    ->setCellValue('B5', $totalp_r_i_m)
                    ->setCellValue('C5', $tt3)
                    ->setCellValue('D5', $tg3)
                    ->setCellValue('E5', $tpfm3)
                    ->setCellValue('A6', 'REPROCESOS DE INCLUSIONES DEVUELTAS')
                    ->setCellValue('B6', $totalp_r_i_d_i_m)
                    ->setCellValue('C6', $tt4)
                    ->setCellValue('D6', 'N/A')
                    ->setCellValue('E6', 'N/A')
                    ->setCellValue('A7', 'TOTAL')
                    ->setCellValue('B7', $totalp_i_m + $totalp_dev_i_m + $totalp_r_i_m + $totalp_r_i_d_i_m)
                    ->setCellValue('C7', $tt + $tt2 + $tt3 + $tt4)
                    ->setCellValue('D7', $tg + $tg3)
                    ->setCellValue('E7', $tpfm + $tpfm3);        
        $i = 9;
        
        $j = 1;
        while ($j <= $nrodiasmes) {
        $caracteres = strlen($j);
        if ($caracteres == 1){
            $newfecha = $anio.'-'.$mes.'-'.'0'.$j;
        }else{
            $newfecha = $anio.'-'.$mes.'-'.$j; 
        }        
        $reporte = Reporte::find()->where(['like','fecha_gestion',$newfecha])->one(); 
        //inclusiones
        if ($reporte){$inc_recibida_p_d_a = $reporte->inc_recibida_p_d_a;}else{$inc_recibida_p_d_a = 0;}
        if ($reporte){$inc_recibida = $reporte->inc_recibida;}else{$inc_recibida = 0;}
        if ($reporte){$inc_recibida_g = $reporte->inc_recibida_g;}else{$inc_recibida_g = 0;}
        if ($reporte){$inc_recibida_p_f_d = $reporte->inc_recibida_p_f_d;}else{$inc_recibida_p_f_d = 0;}
        //devueltas
        if ($reporte){$inc_dev_p_d_a = $reporte->inc_dev_p_d_a;}else{$inc_dev_p_d_a = 0;}
        if ($reporte){$inc_dev_recibida = $reporte->inc_dev_recibida;}else{$inc_dev_recibida = 0;}
        if ($reporte){$inc_dev_g = $reporte->inc_dev_g;}else{$inc_dev_g = 0;}
        if ($reporte){$inc_dev_p_f_d = $reporte->inc_dev_p_f_d;}else{$inc_dev_p_f_d = 0;}
        //reprocesos
        if ($reporte){$rep_inc_p_d_a = $reporte->rep_inc_p_d_a;}else{$rep_inc_p_d_a = 0;}
        if ($reporte){$rep_inc_recibida = $reporte->rep_inc_recibida;}else{$rep_inc_recibida = 0;}
        if ($reporte){$rep_inc_g = $reporte->rep_inc_g;}else{$rep_inc_g = 0;}
        if ($reporte){$rep_inc_p_f_d = $reporte->rep_inc_p_f_d;}else{$rep_inc_p_f_d = 0;}
        //reprocesos inc devueltas
        if ($reporte){$rep_inc_dev_p_d_a = $reporte->rep_inc_dev_p_d_a;}else{$rep_inc_dev_p_d_a = 0;}
        if ($reporte){$rep_inc_dev_recibida = $reporte->rep_inc_dev_recibida;}else{$rep_inc_dev_recibida = 0;}
        if ($reporte){$rep_inc_dev_g = $reporte->rep_inc_dev_g;}else{$rep_inc_dev_g = 0;}
        if ($reporte){$rep_inc_dev_p_f_d = $reporte->rep_inc_dev_p_f_d;}else{$rep_inc_dev_p_f_d = 0;}
        $i = $i+1;
            $objPHPExcel->getActiveSheet()->mergeCells('A'.($i-1).':E'.($i-1));
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($i-1), $j.'-'.$mes.'-'.$anio);                            
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, 'CASAUTICAS')
                ->setCellValue('B' . $i, 'PENDIENTES DEL DIA ANTERIOR')
                ->setCellValue('C' . $i, 'RECIBIDAS')
                ->setCellValue('D' . $i, 'GESTIONADAS')
                ->setCellValue('E' . $i, 'PENDIENTES AL FINALIZAR EL DIA');
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . ($i+1), 'INCLUSIONES')
                ->setCellValue('B' . ($i+1), $inc_recibida_p_d_a)
                ->setCellValue('C' . ($i+1), $inc_recibida)
                ->setCellValue('D' . ($i+1), $inc_recibida_g)
                ->setCellValue('E' . ($i+1), $inc_recibida_p_f_d)    
                ->setCellValue('A' . ($i+2), 'INCLUSIONES DEVUELTAS')
                ->setCellValue('B' . ($i+2), $inc_dev_p_d_a)
                ->setCellValue('C' . ($i+2), $inc_dev_recibida)
                ->setCellValue('D' . ($i+2), $inc_dev_g)
                ->setCellValue('E' . ($i+2), $inc_dev_p_f_d)    
                ->setCellValue('A' . ($i+3), 'REPROCESOS INCLUSIONES')
                ->setCellValue('B' . ($i+3), $rep_inc_p_d_a)
                ->setCellValue('C' . ($i+3), $rep_inc_recibida)
                ->setCellValue('D' . ($i+3), $rep_inc_g)
                ->setCellValue('E' . ($i+3), $rep_inc_p_f_d)    
                ->setCellValue('A' . ($i+4), 'REPROCESOS DE INC DEVUELTAS')
                ->setCellValue('B' . ($i+4), $rep_inc_dev_p_d_a)
                ->setCellValue('C' . ($i+4), $rep_inc_dev_recibida)
                ->setCellValue('D' . ($i+4), $rep_inc_dev_g)
                ->setCellValue('E' . ($i+4), $rep_inc_dev_p_f_d)    
                ->setCellValue('A' . ($i+5), 'TOTAL')
                ->setCellValue('B' . ($i+5), $inc_recibida_p_d_a + $inc_dev_p_d_a + $rep_inc_p_d_a + $rep_inc_dev_p_d_a)
                ->setCellValue('C' . ($i+5), $inc_recibida + $inc_dev_recibida + $rep_inc_recibida + $rep_inc_dev_recibida)
                ->setCellValue('D' . ($i+5), $inc_recibida_g + $inc_dev_g + $rep_inc_g + $rep_inc_dev_g)
                ->setCellValue('E' . ($i+5), $inc_recibida_p_f_d + $inc_dev_p_f_d + $rep_inc_p_f_d + $rep_inc_dev_p_f_d);
            $j++;
            $i = $i + 6;
        $i++;    
        }

        $objPHPExcel->getActiveSheet()->setTitle('informe');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="informe.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }

}
