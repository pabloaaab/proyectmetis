<?php

namespace app\controllers;

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
use app\models\Proceso;
use app\models\FormProceso;
use yii\helpers\Url;
use app\models\FormFiltroProceso;


    class ProcesoController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroProceso;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->buscar);
                        $table = Proceso::find()
                            ->where(['like', 'proceso', $search])                                                        
                            ->orderBy('id_proceso asc');
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
                } else {
                    $table = Proceso::find()                        
                        ->orderBy('id_proceso asc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count(),
                    ]);
                    $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();
                }
                return $this->render('index', [
                    'model' => $model,
                    'form' => $form,
                    'search' => $search,
                    'pagination' => $pages,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }

        public function actionNuevo()
        {
            $model = new FormProceso;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Proceso;
                    $table->proceso = $model->proceso;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        //$model->proceso = null;
                    } else {
                        $msg = "error";
                    }
                } else {
                    $model->getErrors();
                }
            }

            return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
        }

        public function actionEditar()
        {
            $model = new FormProceso;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Proceso::find()->where(['id_proceso' => $model->id_proceso])->one();
                    if ($table) {
                        $table->id_proceso = $model->id_proceso;
                        $table->proceso = $model->proceso;
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";
                        }
                    } else {
                        $msg = "El registro seleccionado no ha sido encontrado";
                        $tipomsg = "danger";
                    }
                } else {
                    $model->getErrors();
                }
            }

            if (Yii::$app->request->get("id_proceso")) {
                $id_proceso = Html::encode($_GET["id_proceso"]);
                $table = Proceso::find()->where(['id_proceso' => $id_proceso])->one();
                if ($table) {
                    $model->id_proceso = $table->id_proceso;
                    $model->proceso = $table->proceso;
                } else {
                    return $this->redirect(["proceso/index"]);
                }
            } else {
                return $this->redirect(["proceso/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }
        
        public function actionEliminar($id) {
        if (Yii::$app->request->post()) {
            $proceso = Proceso::findOne($id);
            if ((int) $id) {
                try {
                    Proceso::deleteAll("id_proceso=:id_proceso", [":id_proceso" => $id]);
                    Yii::$app->getSession()->setFlash('success', 'Registro Eliminado.');
                    $this->redirect(["proceso/index"]);
                } catch (IntegrityException $e) {
                    $this->redirect(["proceso/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el proceso ' . $proceso->id_proceso . ' tiene registros asociados');
                } catch (\Exception $e) {

                    $this->redirect(["proceso/index"]);
                    Yii::$app->getSession()->setFlash('error', 'Error al eliminar el proceso ' . $proceso->id_proceso . ' tiene registros asociados');
                }
            } else {
                // echo "Ha ocurrido un error al eliminar el cliente, redireccionando ...";
                echo "<meta http-equiv='refresh' content='3; " . Url::toRoute("proceso/index") . "'>";
            }
        } else {
            return $this->redirect(["proceso/index"]);
        }
    }
}