<?php

namespace app\controllers;

use app\models\FormFiltroInformesPagos;
use app\models\Matriculados;
use app\models\Pagos;
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
use app\models\UsuarioDetalle;
use PHPExcel;



class InformepagosController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroInformesPagos;
            //$nivel = null;
            $identificacion = null;
            $fechapago = null;
            $sede = null;
            $tipopago = null;            
            $anio_mes_dia = null;
            $nro_pago = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    //$nivel = Html::encode($form->nivel);
                    $identificacion = Html::encode($form->identificacion);
                    $fechapago = Html::encode($form->fechapago);
                    $sede = Html::encode($form->sede);
                    $tipopago = Html::encode($form->tipo_pago);
                    $anio_mes_dia = Html::encode($form->anio_mes_dia);
                    $nro_pago = Html::encode($form->nro_pago);
                    if ($anio_mes_dia == "dia"){
                        $fechapago = $fechapago;
                    }
                    if ($anio_mes_dia == "mes"){
                        $fechapago = date('Y-m', strtotime($fechapago));
                    }
                    if ($anio_mes_dia == "anio"){
                        $fechapago = date('Y', strtotime($fechapago));
                    }
                    $table = Pagos::find()                            
                            //->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificacion])
                            ->andFilterWhere(['like', 'fecha_registro', $fechapago])
                            ->andFilterWhere(['like', 'tipo_pago', $tipopago])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->andFilterWhere(['=', 'nropago', $nro_pago])
                            ->orderBy('nropago desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                                
                $connection = Yii::$app->getDb();
                if ($identificacion != null){
                    $d1= " and identificacion = '". $identificacion."'";
                }else{
                    $d1= " ";
                }
                if ($fechapago != null){
                    $d2= " and fecha_registro like '%". $fechapago."%'";
                }else{
                    $d2= " ";
                }
                if ($sede != null){
                    $d3= " and sede = '". $sede."'";
                }else{
                    $d3= " ";
                }
                if ($tipopago != null){
                    $d4= " and tipo_pago = '". $tipopago."'";
                }else{
                    $d4= " ";
                }
                if ($nro_pago != null){
                    $d5= " and nropago = '". $nro_pago."'";
                }else{
                    $d5= " ";
                }
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(tipo_pago = 'otros' and anulado = '',total,0))   AS otrospagos,
                        SUM(IF(tipo_pago = 'otros' and anulado = 'si',total,0))   AS otrospagosanulados,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = '',total,0))   AS pagosmedellin,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = 'si',total,0))   AS pagosmedellinanulado,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = '',total,0))   AS pagosrionegro,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = 'si',total,0))   AS pagosrionegroanulado      
                        FROM pagos where nropago <> 0 ".$d1.$d2.$d3.$d4.$d5);
                                            

                $result = $command->queryAll();
                $subtotal = $result[0]['otrospagos'] + $result[0]['pagosmedellin'] + $result[0]['pagosrionegro'];
                $totalanulado = $result[0]['otrospagosanulados'] + $result[0]['pagosmedellinanulado'] + $result[0]['pagosrionegroanulado'];
                $grantotal = $subtotal - $totalanulado;
                } else {
                    $form->getErrors();
                }
                
                if(isset($_POST['excel'])){
                    $table = Pagos::find()                            
                            //->andFilterWhere(['like', 'nivel', $nivel])
                            ->andFilterWhere(['like', 'identificacion', $identificacion])
                            ->andFilterWhere(['like', 'fecha_registro', $fechapago])
                            ->andFilterWhere(['like', 'tipo_pago', $tipopago])
                            ->andFilterWhere(['like', 'sede', $sede])
                            ->andFilterWhere(['=', 'nropago', $nro_pago])
                            ->orderBy('nropago desc');
                    
                    $model = $table->all();
                    $this->actionExcel($model);                    
                }
            } else {
                $table = Pagos::find()                        
                        ->orderBy('nropago desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT 
                        SUM(IF(tipo_pago = 'otros' and anulado = '',total,0))   AS otrospagos,
                        SUM(IF(tipo_pago = 'otros' and anulado = 'si',total,0))   AS otrospagosanulados,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = '',total,0))   AS pagosmedellin,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'medellin' and anulado = 'si',total,0))   AS pagosmedellinanulado,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = '',total,0))   AS pagosrionegro,
                        SUM(IF(tipo_pago = 'mensualidad' and sede = 'rionegro' and anulado = 'si',total,0))   AS pagosrionegroanulado      
                        FROM pagos
                    ");

                $result = $command->queryAll();
                $subtotal = $result[0]['otrospagos'] + $result[0]['pagosmedellin'] + $result[0]['pagosrionegro'];
                $totalanulado = $result[0]['otrospagosanulados'] + $result[0]['pagosmedellinanulado'] + $result[0]['pagosrionegroanulado'];
                $grantotal = $subtotal - $totalanulado;
                if(isset($_POST['excel'])){
                    //$this->actionExcel($model);                    
                }
            }
            
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,                        
                        'result' => $result,
                        'totalanulado' => $totalanulado,
                        'subtotal' => $subtotal,
                        'grantotal' => $grantotal
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionExcel($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nro Pago')
                    ->setCellValue('B1', 'Estudiante')
                    ->setCellValue('C1', 'Pago')
                    ->setCellValue('D1', 'Tipo Pago')
                    ->setCellValue('E1', 'Valor Pago')
                    ->setCellValue('F1', 'Fecha Pago')
                    ->setCellValue('G1', 'Sede')
                    ->setCellValue('H1', 'Nivel')
                    ->setCellValue('I1', 'Observaciones')
                    ->setCellValue('J1', 'Anulado');

        $i = 2;
        
        foreach ($model as $val) {
            if ($val->anulado == "") {
                $anulado = "NO";
            } else {
                $anulado = "SI";
            }                        
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->nropago)
                    ->setCellValue('B' . $i, $val->entificacion->nombreEstudiante)
                    ->setCellValue('C' . $i, $val->mensualidad)
                    ->setCellValue('D' . $i, $val->ttpago)
                    ->setCellValue('E' . $i, $val->total)
                    ->setCellValue('F' . $i, $val->fecha_registro)
                    ->setCellValue('G' . $i, $val->sede)
                    ->setCellValue('H' . $i, $val->nivel)
                    ->setCellValue('I' . $i, $val->observaciones)
                    ->setCellValue('J' . $i, $anulado);
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
