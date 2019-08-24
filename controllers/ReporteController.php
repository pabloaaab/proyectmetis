<?php

namespace app\controllers;

use app\models\FormFiltroReporte;
use app\models\Reporte;
use app\models\Cliente;
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



class ReporteController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroReporte;            
            $placa = null;
            $fecha_enviado_desde = null;
            $fecha_enviado_hasta = null;
            $id_proceso = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $placa = Html::encode($form->placa);
                    $fecha_enviado_desde = Html::encode($form->fecha_enviado_desde);
                    $fecha_enviado_hasta = Html::encode($form->fecha_enviado_hasta);
                    $id_proceso = Html::encode($form->id_proceso);
                    if ($fecha_enviado_desde <> null or $fecha_enviado_hasta <> null){
                        $dato1 = $fecha_enviado_desde.' 00:00:00';
                        $dato2 = $fecha_enviado_desde.' 23:59:59';
                    }else{
                        $dato1 = null;
                        $dato2 = null;
                    }
                    $table = Reporte::find()                                                        
                            ->andFilterWhere(['like', 'placa', $placa])
                            ->andFilterWhere(['>=', 'fecha_enviado', $dato1])
                            ->andFilterWhere(['<=', 'fecha_enviado', $dato2])
                            ->andFilterWhere(['=', 'id_proceso', $id_proceso])                            
                            ->orderBy('fecha_enviado desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();                                                               
                } else {
                    $form->getErrors();
                }
                
                if(isset($_POST['excel'])){
                    $table = Reporte::find()                                                        
                            ->andFilterWhere(['like', 'placa', $placa])
                            ->andFilterWhere(['>=', 'fecha_enviado', $dato1])
                            ->andFilterWhere(['<=', 'fecha_enviado', $dato2])
                            ->andFilterWhere(['like', 'id_proceso', $id_proceso])                            
                            ->orderBy('fecha_enviado desc');
                    
                    $model = $table->all();
                    $this->actionExcel($model);                    
                }
            } else {
                $table = Reporte::find()
                        ->where(['=','id_reporte',0])
                        ->orderBy('fecha_enviado desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all(); 
                if(isset($_POST['excel'])){
                    $this->actionExcel($model);                    
                }
            }
            
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,                                               
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
                    ->setCellValue('A1', 'Id')
                    ->setCellValue('B1', 'Cliente')
                    ->setCellValue('C1', 'Proceso')
                    ->setCellValue('D1', 'Fecha Proceso')
                    ->setCellValue('E1', 'Tema')
                    ->setCellValue('F1', 'Placa');

        $i = 2;
        
        foreach ($model as $val) {                                    
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_reporte)
                    ->setCellValue('B' . $i, $val->cliente->nombre_completo)
                    ->setCellValue('C' . $i, $val->proceso->proceso)
                    ->setCellValue('D' . $i, $val->fecha_enviado)
                    ->setCellValue('E' . $i, $val->tema)
                    ->setCellValue('F' . $i, $val->placa);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Reporte');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte.xlsx"');
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
    
    protected function findModel($id)
    {
        if (($model = Reporte::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionImprimir($id)
    {
                                
        return $this->render('../formato/carta', [
            'model' => $this->findModel($id),
            
        ]);
    }        

    public function actionCarta($id) {
        
        return $this->renderAjax('carta', [
                    'model' => $this->findModel($id),                    
        ]);
    
    }
    
    public function actionLlamada($id) {
        
        return $this->renderAjax('llamada', [
                    'model' => $this->findModel($id),                    
        ]);
    
    }
    
    public function actionSms($id) {
        
        return $this->renderAjax('sms', [
                    'model' => $this->findModel($id),                    
        ]);
    
    }

}
