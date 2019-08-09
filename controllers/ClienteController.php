<?php

namespace app\controllers;

use app\models\FormFiltroCliente;
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



class ClienteController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroCliente;            
            $placa = null;
            $email = null;
            $nombres = null; 
            $apellidos = null; 
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $placa = Html::encode($form->placa);
                    $email = Html::encode($form->email);
                    $nombres = Html::encode($form->nombres);
                    $apellidos = Html::encode($form->apellidos);                                        
                    $table = Cliente::find()                                                        
                            ->andFilterWhere(['like', 'placa', $placa])
                            ->andFilterWhere(['like', 'email', $email])
                            ->andFilterWhere(['like', 'nombres', $nombres])                            
                            ->andFilterWhere(['like', 'apellidos', $apellidos])                            
                            ->orderBy('id_cliente desc');
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
                    $table = Cliente::find()                                                        
                            ->andFilterWhere(['like', 'placa', $placa])
                            ->andFilterWhere(['like', 'email', $email])
                            ->andFilterWhere(['like', 'nombres', $nombres])                            
                            ->andFilterWhere(['like', 'apellidos', $apellidos])                            
                            ->orderBy('id_cliente desc');
                    
                    $model = $table->all();
                    $this->actionExcel($model);                    
                }
            } else {
                $table = Cliente::find()                        
                        ->orderBy('id_cliente desc');
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
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'Placa')
                    ->setCellValue('E1', 'Telefono_1')
                    ->setCellValue('F1', 'Direccion_1');

        $i = 2;
        
        foreach ($model as $val) {                                    
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id_cliente)
                    ->setCellValue('B' . $i, $val->nombre_completo)
                    ->setCellValue('C' . $i, $val->email)
                    ->setCellValue('D' . $i, $val->placa)
                    ->setCellValue('E' . $i, $val->telefono_1)
                    ->setCellValue('F' . $i, $val->direccion_1);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Clientes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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

}
