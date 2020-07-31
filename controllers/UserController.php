<?php

// Este controlador lo reescribí únicamente para agregar el rol predeterminado

namespace app\controllers;

use Yii;
use mdm\admin\models\form\Signup;
use mdm\admin\controllers\UserController as AdminUserController;
use app\models\User;
use app\models\UserChangePassword;
use app\models\UserSearch;
use app\models\BibliotecaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class UserController extends AdminUserController
{
    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(\Yii::$app->getRequest()->post())) {
            if ($user = $model->signup()) {
            	/*$auth = \Yii::$app->authManager;
            	$authorRole = $auth->getRole('operador');
        		$auth->assign($authorRole, $user->getId());*/
                return $this->goHome();
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Lists all Persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    

     /**
    * Displays a single User model.
    * @param integer $id
    * @return mixed
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionView($id)
   {
       
       $searchModel = new BibliotecaSearch();
       $listaBibliotecas = $searchModel->search(Yii::$app->request->queryParams);
       return $this->render('view', [
           'model' => $this->findModel($id),
           'listaBibliotecas' => $listaBibliotecas
       ]);
   }
   /**
    * Creates a new User model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
   public function actionCreate()
   {
       $model = new User();
       if ($model->load(Yii::$app->request->post()) && $model->save()) {
           return $this->redirect(['view', 'id' => $model->id]);
       }
       return $this->render('create', [
           'model' => $model,
       ]);
   }
   /**
    * Updates an existing User model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionUpdate($id)
   {
       if (Yii::$app->user->identity->isFullAdmin) {
          return $this->redirect(['admin-update', 'id' => $id]);
       }
       $model = $this->findModel($id);
       if ($model->load(Yii::$app->request->post()) && $model->save()) {
          if (($library=Yii::$app->session->get('library')) && !is_null($model->permiso)) {
            $model->setPermiso(
              $library->id,
              $model->permiso);
          }
          return $this->redirect(['view', 'id' => $model->id]);
       }
       return $this->render('update', [
           'model' => $model,
       ]);
   }

   public function actionAdminChangePassword($id)
    {
        $model = new UserChangePassword();
        $model->id=$id;
        $model->load(Yii::$app->getRequest()->post());
        $user = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change($user)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('change-password', [
                'model' => $model,
                'user' => $user,
        ]);
    }
   
   public function actionAdminUpdate($id)
   {
       $model = $this->findModel($id);
       if ($model->load(Yii::$app->request->post()) && $model->save()) {
           $model->setPermisosEfectivos();
           return $this->redirect(['view', 'id' => $model->id]);
       }
       return $this->render('adminUpdate', [
           'model' => $model,
       ]);
   }
   /**
    * Deletes an existing User model.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @param integer $id
    * @return mixed
    * @throws NotFoundHttpException if the model cannot be found
    */
   public function actionDelete($id)
   {
       $this->findModel($id)->delete();
       return $this->redirect(['index']);
   }
   /**
    * Finds the User model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return User the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
       if (($model = User::findOne($id)) !== null) {
           return $model;
       }
       throw new NotFoundHttpException('The requested page does not exist.');
   }
}