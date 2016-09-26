<?php
namespace app\controllers;
use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\Role;
use app\models\Ticket;
use app\models\TicketAttachment;
use app\models\TicketLog;
use app\models\Helpdesk;
use app\models\Employee;
/**
 * Site controller
 */
class TicketController extends Controller {
    public $class='Ticket';
    
    public function actions() {
    	if(Yii::$app->user->isGuest){
    		 Yii::$app->session->setFlash('msg',Yii::t('app/message','msg you must login'));
    		return $this->redirect(['site/login']);
    	}
    }
    
    /**
     * Behaviour Function
     * Control Access Control Rule
     */
    public function behaviors() {
    	return [
    		'access' => [
    			'class' => AccessControl::className(),
    			'ruleConfig' => [
    				'class' => Role::className(),
    			],
    			'only' => ['index','view','new','remove'],
    				'rules' => [
    					[
    						'allow' => true,
    						'actions' => ['index','view','new','remove'],
    						'roles' => [
    							Helpdesk::ROLE_USER,
    						],
    					],
    				],
    				'denyCallback' => function ($rule, $action) {
    					return $this->redirect(['ticket/index']);
    				},
    			'only' => ['management','set-helpdesk','myjob','open'],
    				'rules' => [
    					[
    						'allow' => true,
    						'actions' => ['management','set-helpdesk','myjob','open'],
    						'roles' => [
    							Helpdesk::ROLE_HELPDESK,
    						],
    					],
    				],
    				'denyCallback' => function ($rule, $action) {
    					return $this->redirect(['ticket/management']);
    				},
    			],
    		];
    }
    
    /**
     * Ticket for end user Access
     */
    public function actionIndex() {
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams)) {
            //process here
        }
        return $this->render('ticket_index',[
            'model' => $model,
            'dataProvider' => $model->getAllDataProvider(Yii::$app->request->queryParams,Yii::$app->user->getId())
        ]); 
    }
    
    public function actionNew() {
        $model = new Ticket(['scenario' => 'new_ticket']);
        if($model->load(Yii::$app->request->post()) && $model->getSave()) {
            Yii::$app->session->setFlash('message','<div class="alert alert-success"> <i class="fa fa-check"></i> '. Yii::t('app/message','msg ticket has been insert').'</div>');
            $this->redirect(['ticket/index'],301);
        } else {
        	TicketAttachment::deleteAll('ticket_id = :id and ticket_md5 = :md5 ',[':id' => 0 , ':md5' => md5(Yii::$app->user->getId()) ]);
        }
        return $this->render('ticket_form',[
            'model' => $model,
        ]);    
    }
    
    public function actionView($id) {
        $model = new TicketLog();
        $query = Ticket::findOne($id);
        $logQuery = $model->getTicketLogData($id);
        if($model->load(Yii::$app->request->post()) && $model->getSave($id)) {
        	$status_id = $query->id;
        	if($status_id == 0)
        		$redirect = Yii::$app->request->hostInfo.\yii\helpers\Url::to(['ticket/index']);
        	else
        		$redirect = '';	
        	Yii::$app->response->format = 'json';
            return [
            	'success' => true,
            	'comment' => $model->log_desc,
            	'date' => date('d/m/Y H:i:s'),
            	'employee'=>Yii::$app->user->identity->EmployeeFirstName,'redirect' => $redirect
            ];
        }
        
        //change unread to read in ticket log
        TicketLog::updateAll(['ticket_read' => 0],['to_employee_id'=>Yii::$app->user->getId(),'ticket_id'=>$id]);
        $model->ticket_rating = 3;
        return $this->render('ticket_view',[
            'model' => $model,
            'query' => $query,
        	'attacthment' => TicketAttachment::find()->where(["ticket_id" => $id])->all(),	
            'logQuery' => $logQuery
        ]);
    }
    
    public function actionRemove($id) {
    	$ticket = Ticket::findOne($id);
    	if($ticket->employee_id == Yii::$app->user->getId()) {
    		Ticket::deleteAll('ticket_id = :id',[':id' => $id]);
    		TicketAttachment::deleteAll('ticket_id = :id',[':id' => $id]);
    	}
    	
    	Yii::$app->session->setFlash('message','<div class="alert alert-success"> <i class="fa fa-check"></i> '. Yii::t('app/message','msg ticket has been deleted').'</div>');
    	return $this->redirect(['ticket/index']);
    }
    
    /**
     * Ticket for end user Access
     */
    public function actionRequest() {
    	$model = new Ticket(['scenario'=>'search']);
    	if($model->validate() && $model->load(Yii::$app->request->queryParams)) {
    		//process here
    	}
    	return $this->render('ticket_all_request',[
    		'model' => $model,
    		'dataProvider' => $model->getAllRequestDataProvider(Yii::$app->request->queryParams)
    	]);
    }
    
    public function actionManagement(){
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_management',[
            'model' => $model,
            'dataProvider' => $model->getAllDataProvider(Yii::$app->request->queryParams,null,4)
        ]); 
    }
    
    public function actionSetHelpdesk($id) {
        $model = new Ticket(['scenario'=>'set_helpdesk']);
        
        if($model->load(Yii::$app->request->post()) && $model->getSetHelpdesk($id)) {
            Yii::$app->session->setFlash('msg','<div class="alert alert-success"> <i class="fa fa-check"></i> '. Yii::t('app/message','msg set helpdesk successfully').'</div>');
            return $this->redirect(['ticket/management']);
        }
        
        return $this->render('ticket_set',[
            'model' => $model,
        	'ticket' => $model->find()->from("ticket t")->join('inner join','employee e','e.employee_id = t.employee_id')
        		->join('inner join','ticket_categories tc','tc.id = t.ticket_category_id')
        		->where(['t.ticket_id'=> $id])
        		->select(['*',"CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name ",
        		"DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date","tc.name as ticket_type"])->one(),	
            'attachment' => TicketAttachment::find()->where(['ticket_id'=>$id])->all(),
            'dropDownHelpdesk' => Helpdesk::getDropdownListData(false),
        ]); 
    }
    
    public function actionMyjob() {
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams)){
            //process here
        }
        return $this->render('ticket_myjob',[
            'model' => $model,
            'dataProvider' => $model->getAllDataProvider(Yii::$app->request->queryParams,null,null,Yii::$app->user->getId())
        ]); 
    }
    
    public function actionOpen($id) {
        $model = new TicketLog();
        //change unread to read in ticket log
        $model->updateAll(['ticket_read' => 0],['to_employee_id'=>Yii::$app->user->getId(),'ticket_id'=>$id]);
        if($model->load(Yii::$app->request->post()) && $model->getSave($id,true)) {
            Yii::$app->response->format = 'json';
            return ['success' => true,'comment' => $model->log_desc,'date' => date('d/m/Y H:i:s'),'employee'=>Yii::$app->user->identity->EmployeeFirstName];
        }
        
        return $this->render('ticket_open',[
            'model' => $model,
            'attachtment' => TicketAttachment::find()->where(['ticket_id'=>$id])->all(),
            'val' => Ticket::find()->join('inner join','employee','employee.employee_id=ticket.employee_id')
        				->where(['ticket.ticket_id' => $id])->one(),
            'logs' =>  $model->getTicketLogData($id)
        ]);
    }
    
    public function actionOrder() {
        $model = new Ticket(['scenario' => 'order_ticket']);
        if($model->load(Yii::$app->request->post()) && $model->getSave(true)){
             Yii::$app->session->setFlash('msg','<div class="alert alert-success"> <i class="fa fa-check"></i> '. Yii::t('app/message','msg ticket has been insert').'</div>');
            return $this->redirect(['ticket/list'],301);
        } else {
        	//delete attachtment
        	TicketAttachment::deleteAll('ticket_id = :id and ticket_md5 =:md5 ',[':id' => 0,':md5' => md5(Yii::$app->user->getId())]);
        }

        return $this->render('ticket_order',[
            'model' => $model,
            'dropDownEmployee' => Employee::getEmployeeDropdownList(),
            'dropDownHelpdesk' => Helpdesk::getDropdownListData(false),
        ]);    
    }
    
    public function actionUpload(){
        $fileName = 'file';
        $uploadPath = 'uploads';
        $user = Yii::$app->user->identity->EmployeeID;
        $userUploadPath = $uploadPath.'/'.$user;
        
        if (isset($_FILES[$fileName])) {
            \yii\helpers\FileHelper::createDirectory($userUploadPath,0777,true);
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($userUploadPath . '/' . 'attachment'.date('Y-m-d-H-i-s').'.'.$file->extension)) {
                //Now save file data to database
                $ticket_att = new \app\models\TicketAttachment();
                $ticket_att->ticket_id = 0;
                $ticket_att->ticket_md5 = md5(Yii::$app->user->getId());
                $ticket_att->attachment_file = $userUploadPath . '/' . 'attachment'.date('Y-m-d-H-i-s').'.'.$file->extension;
                $ticket_att->created_at = date("Y-m-d H:i:s");
                $ticket_att->created_by = Yii::$app->user->getId();
                $ticket_att->insert();
                echo \yii\helpers\Json::encode($file);
            }
            
            return true;
        }
        return false;
    }
    
    public function actionList(){
        if(!Yii::$app->session->get('helpdesk')) return $this->redirect(['ticket/index']);
        $model = new Ticket(['scenario'=>'search']);
        if($model->validate() && $model->load(Yii::$app->request->queryParams) && isset($_GET['search'])){
            //process here
        }
        return $this->render('ticket_list',[
            'model' => $model,
            'dataProvider' => $model->getAllDataProvider(Yii::$app->request->queryParams)
        ]); 
    }
    
    /**
     * Closed ticket for helpdesk
     * @param unknown $id
     * @return \yii\web\Response
     */
    public function actionClosed($id) {
    	$ticket = Ticket::findOne(["ticket_id" => $id]);
    	if($ticket->ticket_status == 0) {
    		Ticket::updateAll(['ticket_status_helpdesk' => 0], ['ticket_id' => $id]);
    	}
    	Yii::$app->session->setFlash('msg','<div class="alert alert-success"> <i class="fa fa-check"></i> '. Yii::t('app/message','msg ticket has been close').'</div>');
    	return $this->redirect(['ticket/myjob']);
    }
}
    