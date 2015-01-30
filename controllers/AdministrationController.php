<?php
namespace app\controllers;
use yii;
use yii\web;
use yii\web\Controller;
use app\models\Helpdesk;
use app\models\Employee;

class AdministrationController extends Controller{
    public $class;
    public $title;
    public $url=[];
    
    public function actions(){
        if(Yii::$app->user->isGuest) $this->redirect(['site/login'],302);
    }
    
    public function actionHelpdesk(){
        $model = new Helpdesk(['scenario'=>'search']);
       
        if($model->validate() && $model->load(Yii::$app->request->queryParams)){
            //proses here
        }
        $dataProvider = $model->getHelpdeskData(Yii::$app->request->queryParams);
        $this->url['add'] = 'administration/helpdesk_add';
        return $this->render('helpdesk',[
            'dataProvider' => $dataProvider,
            'model' => $model,
            'role' => $model->getHelpdeskRole(true),
        ]);
    }
    
    public function actionHelpdesk_add(){
        $this->title = Yii::t('app/backend','add new');
        $model = new Helpdesk(['scenario'=>'insert']);
        if (($model->load(Yii::$app->request->post())) && ($model->getSave())){
            return $this->redirect(['administration/helpdesk']);
        }
        $employee = new Employee();
        return $this->render('helpdesk_form',[
            'model' => $model,
            'query' => 0,
            'employee' => $employee->getEmployeeDropdownList(),
            'role' => $model->getHelpdeskRole(false),
        ]
        );
    }
    
    public function actionHelpdesk_delete($id){
        $model = new Helpdesk();
        $query = $model->findOne(['helpdesk_id'=>$id]);
        if(!$query){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg no data selection'));
        } 
        else{
            Helpdesk::deleteAll('helpdesk_id = :id',[':id' => $id]);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg delete success'));
        }
        $this->redirect(['administration/helpdesk']);
    }
    
    public function actionGeneral(){
        $id = Yii::$app->user->getId();
        $query = Employee::findOne($id);
        $model = new Employee(['scenario'=>'update_account']);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->getUpdateProfile($id)){
            $query = Employee::findOne($id);
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg update account successfully'));
        }
        return $this->render('setting',[
            'model' => $model,
            'query' => $query,
            'tabPage' => 'setting_general'
        ]);
    }
    
    public function actionPassword(){
        $id = Yii::$app->user->getId();
        $model = new \app\models\User(['scenario'=>'update_password']);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->getUpdatePassword($id)){
            Yii::$app->session->setFlash('msg',Yii::t('app/message','msg update password successfully'));
        }
        return $this->render('setting',[
            'model' => $model,
            'query' => 0,
            'tabPage' => 'setting_password'
        ]);
    }
}
