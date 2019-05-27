<?php

namespace app\controllers;

use app\models\FormFiltroConsulta;
use app\models\OstThreadEvent;
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
                    $table = OstThreadEvent::find()                                                        
                            ->andFilterWhere(['like', 'timestamp', $fecha])                            
                            ->orderBy('id asc')
                            ->all();
                    $model = $table;
                    $porciones = explode("-", $fecha);
                    $dia = $porciones[2];
                    $mes = $porciones[1];
                    $anio = $porciones[0];
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

        $objPHPExcel->setActiveSheetIndex(0)           
                    //->setCellValue('A1', $anio)
                    ->setCellValue('A2', 'CASUISTICAS')
                    ->setCellValue('B2', 'PENDIENTES AL INICIAR EL MES')
                    ->setCellValue('C2', 'RECIBIDAS')
                    ->setCellValue('D2', 'GESTIONADAS')
                    ->setCellValue('E2', 'PENDIENTES AL FINALIZAR EL MES')
                    ->setCellValue('A3', 'INCLUSIONES')
                    ->setCellValue('B3', 166)
                    ->setCellValue('C3', 1162)
                    ->setCellValue('D3', 812)
                    ->setCellValue('E3', 204)
                    ->setCellValue('A4', 'INCLUSIONES DEVUELTAS')
                    ->setCellValue('B4', 0)
                    ->setCellValue('C4', 312)
                    ->setCellValue('D4', 'N/A')
                    ->setCellValue('E4', 'N/A')
                    ->setCellValue('A5', 'REPROCESOS DE INCLUSIONES')
                    ->setCellValue('B5', 11)
                    ->setCellValue('C5', 34)
                    ->setCellValue('D5', 45)
                    ->setCellValue('E5', 0)
                    ->setCellValue('A6', 'REPROCESOS DE INCLUSIONES DEVUELTAS')
                    ->setCellValue('B6', 0)
                    ->setCellValue('C6', 0)
                    ->setCellValue('D6', 'N/A')
                    ->setCellValue('E6', 'N/A')
                    ->setCellValue('A7', 'TOTAL')
                    ->setCellValue('B7', 177)
                    ->setCellValue('C7', 1196)
                    ->setCellValue('D7', 857)
                    ->setCellValue('E7', 204);        
        $i = 9;
        
        $j = 1;
        while ($j <= $nrodiasmes) {
        
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
                ->setCellValue('B' . ($i+1), 0)
                ->setCellValue('C' . ($i+1), 0)
                ->setCellValue('D' . ($i+1), 0)
                ->setCellValue('E' . ($i+1), 0)    
                ->setCellValue('A' . ($i+2), 'INCLUSIONES DEVUELTAS')
                ->setCellValue('B' . ($i+2), 0)
                ->setCellValue('C' . ($i+2), 0)
                ->setCellValue('D' . ($i+2), 0)
                ->setCellValue('E' . ($i+2), 0)    
                ->setCellValue('A' . ($i+3), 'REPROCESOS INCLUSIONES')
                ->setCellValue('B' . ($i+3), 0)
                ->setCellValue('C' . ($i+3), 0)
                ->setCellValue('D' . ($i+3), 0)
                ->setCellValue('E' . ($i+3), 0)    
                ->setCellValue('A' . ($i+4), 'REPROCESOS DE INC DEVUELTAS')
                ->setCellValue('B' . ($i+4), 0)
                ->setCellValue('C' . ($i+4), 0)
                ->setCellValue('D' . ($i+4), 0)
                ->setCellValue('E' . ($i+4), 0)    
                ->setCellValue('A' . ($i+5), 'TOTAL')
                ->setCellValue('B' . ($i+5), 0)
                ->setCellValue('C' . ($i+5), 0)
                ->setCellValue('D' . ($i+5), 0)
                ->setCellValue('E' . ($i+5), 0);
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
