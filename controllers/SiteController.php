<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Employee;


/**
 * Site controller
 */
class SiteController extends Controller {
    public $class="Site";
    public $function="";
    public $title="";
	
    public function actions() {
    	//do process
    }
	
    public function actionIndex() {
    	if(!Yii::$app->user->isGuest) {
    		return $this->redirect(['ticket/new']);
    	}
        return $this->render('index');
    }

    public function actionLogin() {
    	if(!Yii::$app->user->isGuest) {
    		return $this->redirect(['ticket/new']);
    	}
    	
		$model = new Employee(['scenario'=>'login']);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->getLogin()) {
		    $helpdesk = new \app\models\Helpdesk();
		    $helpdesk = $helpdesk->find()
		    ->where(['employee_id'=>Yii::$app->user->getId()])
		    ->one();
		    if(count($helpdesk)>0)
			Yii::$app->session->set('helpdesk',Yii::$app->user->getId()); 
		    return $this->redirect(['ticket/new']);
        } 
        return $this->render('login',[
            'model' => $model,
	    	'employee' => $model->getEmployeeBpjs()
        ]);
    }			
    
    public function actionLogout(){
        Yii::$app->user->logout();
		Yii::$app->session->set('helpdesk','');
        return $this->goHome();
    }
    
    public function actionError(){
		echo 'ERROR 404';
    }
}
