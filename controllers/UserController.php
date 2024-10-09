<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\models\UserForm;

class UserController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();
        return $this->render('index', ['users' => $users]);
    }

    public function actionCreate()
    {
        $model = new UserForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->username = $model->username;
            $user->setPassword($model->password);
            $user->is_admin = $model->is_admin;
            $user->generateAuthKey();
            $user->generateAccessToken();
            $user->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $model = new UserForm();
        $model->username = $user->username;
        $model->is_admin = $user->is_admin;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->username = $model->username;
            if (!empty($model->password)) {
                $user->setPassword($model->password);
            }
            $user->is_admin = $model->is_admin;
            $user->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model, 'user' => $user]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}