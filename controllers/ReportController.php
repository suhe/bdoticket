<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\Ticket;

class ReportController extends Controller {
    
    public $class='Report';
    
    public function actions(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket_index']);
    }
    
    public function actionPerformance() {
        $model = new Ticket(['scenario' => 'search']);
        $model->ticket_from_date = date("d/m/Y");
        $model->ticket_to_date = date("d/m/Y");
        if($model->load(Yii::$app->request->queryParams)){
            //$model->refresh();
        }
        
        return $this->render('report_performance',[
            'model' =>  $model,
            'params'=> Yii::$app->request->queryParams,
        ]); 
    }
    
    public function actionCategory() {
    	$model = new Ticket(['scenario' => 'search']);
    	$model->ticket_from_date = date("d/m/Y");
    	$model->ticket_to_date = date("d/m/Y");
    	if($model->load(Yii::$app->request->queryParams)){
    		//$model->refresh();
    	}
    
    	return $this->render('report_category',[
    			'model' =>  $model,
    			'params'=> Yii::$app->request->queryParams,
    	]);
    }
    
    
}