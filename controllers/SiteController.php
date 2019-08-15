<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Codeception\Lib\HelperModule;
use yii\base\Model;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\FormRegister;
use app\models\FormFiltroUsuarios;
use app\models\FormEditRegister;
use app\models\FormChangepassword;
use app\models\Users;
use app\models\User;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
	public function actionUser()
    {
        return $this->render('user');
    }

    public function actionAdmin()
    {
        return $this->render('admin');
    }

    private function randKey($str='', $long=0)
    {
        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str)-1;
        for($x=0; $x<$long; $x++)
        {
            $key .= $str[rand($start, $limit)];
        }
        return $key;
    }    

    public function actionUsuarios() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroUsuarios;
            $perfil = null;
            $username = null;
            $nombrecompleto = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {
                    $perfil = Html::encode($form->perfil);
                    $username = Html::encode($form->username);
                    $nombrecompleto = Html::encode($form->nombrecompleto);                    
                    $table = Users::find()                            
                            ->andFilterWhere(['like', 'perfil', $perfil])
                            ->andFilterWhere(['like', 'username', $username])
                            ->andFilterWhere(['like', 'nombrecompleto', $nombrecompleto])                            
                            ->orderBy('perfil asc');
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
                $table = Users::find()
                        ->orderBy('perfil asc');
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
            return $this->render('usuarios', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionRegister()
    {
        //Creamos la instancia con el model de validación
        $model = new FormRegister;
        $tipomsg = null;
        //Mostrará un mensaje en la vista cuando el usuario se haya registrado
        $msg = null;

        //Validación mediante ajax
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        //Validación cuando el formulario es enviado vía post
        //Esto sucede cuando la validación ajax se ha llevado a cabo correctamente
        //También previene por si el usuario tiene desactivado javascript y la
        //validación mediante ajax no puede ser llevada a cabo
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                //Preparamos la consulta para guardar el usuario
                $table = new Users;
                $table->username = $model->username;
                $table->email = $model->email;
                //Encriptamos el password
                $table->password = crypt($model->password, Yii::$app->params["salt"]);
                //Creamos una cookie para autenticar al usuario cuando decida recordar la sesión, esta misma
                //clave será utilizada para activar el usuario
                $table->authKey = $this->randKey("abcdef0123456789", 200);
                //Creamos un token de acceso único para el usuario
                $table->accessToken = $this->randKey("abcdef0123456789", 200);
                $table->activate = 1;
                $table->nombrecompleto = $model->nombrecompleto;
                $table->role = $model->perfil;
                if ($table->role == 1){
                    $perfil = "Usuario";
                } 
                if ($table->role == 2){
                        $perfil = "Administrador"; 
                }                
                $table->perfil = $perfil;                
                //Si el registro es guardado correctamente
                if ($table->insert())
                {
                    $msg = "Registro realizado correctamente";
                }
                else
                {
                    $msg = "Ha ocurrido un error al llevar a cabo tu registro";
                }

            }
            else
            {
                $model->getErrors();
            }
        }
        return $this->render("register", ["model" => $model, "msg" => $msg,'tipomsg' => $tipomsg]);
    }

    public function actionEditar($id) {
        $model = new FormEditRegister;
        $msg = null;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $table = Users::find()->where(['id' => $id])->one();
                if ($table) {
                    $table->username = $model->username;
                    $table->nombrecompleto = $model->nombrecompleto;
                    $table->role = $model->role;                    
                    $table->email = $model->email;
                    $table->activate = $model->activo;
                    if ($table->role == 2){
                        $perfil = "Administrador";
                    }else{
                        $perfil = "Usuario";
                    }
                    $table->perfil = $perfil;
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["site/usuarios"]);
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        return $this->redirect(["site/usuarios"]);
                    }
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                }
            } else {
                $model->getErrors();
            }
        }

        if (Yii::$app->request->get("id")) {
            $table = Users::find()->where(['id' => $id])->one();

            if ($table) {
                $model->username = $table->username;
                $model->nombrecompleto = $table->nombrecompleto;
                $model->role = $table->role;                
                $model->email = $table->email;
                $model->activo = $table->activate;
            } else {
                return $this->redirect(["site/usuarios"]);
            }
        } else {
            return $this->redirect(["site/usuarios"]);
        }
        return $this->render("editregister", ["model" => $model, "msg" => $msg]);
    }
    
    public function actionChangepassword($id) {
        $model = new FormChangepassword;
        $msg = null;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {

            if ($model->validate()) {
                $table = Users::find()->where(['id' => $id])->one();
                if ($table) {
                    //Encriptamos el password
                    $table->password = crypt($model->password, Yii::$app->params["salt"]);
                    //Creamos una cookie para autenticar al usuario cuando decida recordar la sesión, esta misma
                    //clave será utilizada para activar el usuario
                    $table->authKey = $this->randKey("abcdef0123456789", 200);
                    //Creamos un token de acceso único para el usuario
                    $table->accessToken = $this->randKey("abcdef0123456789", 200);
                    if ($table->update()) {
                        $msg = "El registro ha sido actualizado correctamente";
                        return $this->redirect(["site/usuarios"]);
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        return $this->redirect(["site/usuarios"]);
                    }
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                }
            } else {
                $model->getErrors();
            }
        }

        return $this->render("changepassword", ["model" => $model, "msg" => $msg]);
    }        
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

     /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(["site/login"]);//$this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        //return $this->goHome();
        return $this->redirect(["site/login"]);
    }
    
    public function actionGeneraracceso() {
        ob_clean();
        $matriculasabiertas = Matriculados::find()->where(['=', 'estado2', 'abierta'])->orderBy('consecutivo desc')->all();
        $count = count($matriculasabiertas);
        $mensaje = "";
        if(Yii::$app->request->post()) {
            foreach ($matriculasabiertas as $val){
                $registrado = Users::find()->where(['=','username',$val->identificacion])->one();
                if ($registrado){
                    
                }else{
                    $inscrito = Inscritos::find()->where(['=','identificacion',$val->identificacion])->one();
                    $table = new Users;
                    $table->username = $inscrito->identificacion;
                    $table->email = $inscrito->email;
                    //Encriptamos el password
                    $table->password = crypt($table->username, Yii::$app->params["salt"]);
                    //Creamos una cookie para autenticar al usuario cuando decida recordar la sesión, esta misma
                    //clave será utilizada para activar el usuario
                    $table->authKey = $this->randKey("abcdef0123456789", 200);
                    //Creamos un token de acceso único para el usuario
                    $table->accessToken = $this->randKey("abcdef0123456789", 200);
                    $table->activate = 1;
                    $table->nombrecompleto = $inscrito->nombre1.' '.$inscrito->nombre2.' '.$inscrito->apellido1.' '.$inscrito->apellido2;
                    $table->role = 4;                    
                    $perfil = "Usuario";                     
                    $table->perfil = $perfil;                                        
                    $table->insert();                    
                }                                
            }
        }

        return $this->render('generaracceso', [            

        ]);
    }

}
