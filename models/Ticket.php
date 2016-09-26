<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use app\models\TicketAttachment;

/**
 * Ticket Status
 * 0 = Closed , 1 = Finish , 2 = Proses , 3 = Open , 4 = New
 **/

class Ticket extends ActiveRecord {
    public $id;
    public $ticket_bulk;
    public $closed = 0;
    public $finish = 1;
    public $process = 2;
    public $open = 3;
    public $new=4;
    public $ticket_from_date;
    public $ticket_to_date;
    public $ticket_status_string;
    public $helpdesk_name;
    public $helpdesk_email;
    public $employee_name;
    public $EmployeeFirstName;
    public $EmployeeEmail;
    public $total;
    public $total_day_expired;

    public static function tableName(){
        return 'ticket';
    }
    
    public function rules(){
        return [
          [['ticket_id'],'safe'],
          [['ticket_subject','ticket_category_id','ticket_note'],'required','on'=>['new_ticket']],
          [['ticket_status_string'],'string'],
          [['ticket_from_date','ticket_to_date','ticket_status'],'safe','on'=>'search'],
          [['ticket_helpdesk','ticket_handling'],'required','on'=>['set_helpdesk']],
          [['ticket_subject','ticket_category_id','ticket_note','employee_id','ticket_helpdesk','ticket_handling'],'required','on'=>['order_ticket']],
          [['total'],'integer'],
          [['ticket_from_date','ticket_to_date'],'safe','on'=>'report'],
        ];
    }
    
    public function attributeLabels(){
        return [
            'ticket_id' => Yii::t('app','id'),
            'ticket_date' => Yii::t('app','date'),
            'ticket_subject' => Yii::t('app','subject'),
            'ticket_status' => Yii::t('app','status'),
            'ticket_status_string' => Yii::t('app','status'),
            'ticket_helpdesk' => Yii::t('app','helpdesk'),
            'ticket_handling' => Yii::t('app','handling'),
            'ticket_category_id' => Yii::t('app','type'),
            'ticket_note' => Yii::t('app','note'),
            'helpdesk_name' => Yii::t('app','support'),
            'employee_id' => Yii::t('app','employee'),
            'employee_name' => Yii::t('app','name'),
            'ticket_usercomment' => Yii::t('app','replies'),
            'ticket_from_date' => Yii::t('app','from'),
            'ticket_to_date' => Yii::t('app','to')
        ];
    }
    
    public static function getRatingDropdown(){
        return [
            1 => Yii::t('app','very less'),
            2 => Yii::t('app','less'),
            3 => Yii::t('app','moderate'),
            4 => Yii::t('app','good'),
            5 => Yii::t('app','very good'),
        ];
    }
    
    public static function getStringRating($key){
        switch($key){
            case 1 : $string = Yii::t('app','very less');break;
            case 2 : $string = Yii::t('app','less');break;
            case 3 : $string = Yii::t('app','moderate');break;
            case 4 : $string = Yii::t('app','good');break;
            case 5 : $string = Yii::t('app','very good');break;
            default : $string = Yii::t('app','no comment');break;    
        }
        return $string;
    }
    
    public static function getListStatus() {
    	return [
    		null => Yii::t("app","all"),
    		0 => Yii::t("app","closed"),
    		//1 => Yii::t("app","finish"),
    		2 => Yii::t("app","progress"),
    		3 => Yii::t("app","open"),
    		4 => Yii::t("app","new"),
    	];
    }
    
    public static function getCheckId($id){
        $query = Ticket::findOne($id);
        if(!$query)
           return false;
        return true;
    }
    
    public function getSave($is_helpdesk_fill = false){
        if($this->validate()) {
        	$employee_id = $is_helpdesk_fill == true ? $this->employee_id : Yii::$app->user->getId();
            $model = new Ticket();
            $model->ticket_date = date('Y-m-d');
            $model->employee_id = $employee_id;
            $model->ticket_helpdesk = $is_helpdesk_fill == true ? $this->ticket_helpdesk : null;
            $model->ticket_handling = $is_helpdesk_fill == true ? $this->ticket_handling : null;
            $model->ticket_status = $is_helpdesk_fill == true ?  $this->open : $this->new;
            $model->ticket_usercomment = Yii::t('app/message','msg new ticket');
            $model->ticket_subject = $this->ticket_subject;
            $model->ticket_category_id = $this->ticket_category_id;
            $model->ticket_note = $this->ticket_note;
            $model->ticket_cdate = date('Y-m-d H:i:s');
            $model->ticket_cid = Yii::$app->user->getId();
            $model->insert();
  			//update if attachment
            TicketAttachment::updateAll(['ticket_id' => $model->ticket_id], ['ticket_id' => 0 ,'ticket_md5' => md5(Yii::$app->user->getId())]);
            return true;
        }
    }
    
    public function getAllData($params = array()) {
    	$query = self::find()->from("ticket t")
    	->join('inner join','employee e','e.employee_id=t.employee_id')
    	->join("inner join","ticket_categories tc","tc.id =  t.ticket_category_id")
    	->join('left join','employee h','h.employee_id = t.ticket_helpdesk')
    	->select(["t.ticket_id","e.EmployeeFirstName","e.EmployeeEmail","DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date","ticket_subject",
    			"e.EmployeeFirstName","tc.name as ticket_type","ticket_note","h.EmployeeFirstName as helpdesk_name","h.EmployeeEmail as helpdesk_email",
    			"DATE_FORMAT(t.ticket_udate,'%d/%m/%Y') as ticket_udate","ticket_rating"
    	]); 
    	
    	if(isset($params["ticket_status"])) {
    		$query = $query->where(["ticket_status" => $params["ticket_status"]]);
    	}
    	
    	if(isset($params["ticket_status_helpdesk"])) {
    		$query = $query->where(["ticket_status_helpdesk" => $params["ticket_status_helpdesk"]]);
    	}
    	return $query->all();	
    }
    
    public function getAllDataProvider($params,$employee_id = null, $status = null,$helpdesk = null,$custom_search = array()){
        /*$sql = "SELECT ticket_id,DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date,ticket_subject,
	        	CONCAT(hh.EmployeeFirstName,' ',hh.EmployeeMiddleName,' ',hh.EmployeeLastName) as helpdesk_name,ticket_usercomment,
	        	CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name,ticket_status,
	        	CASE 
	            	WHEN ticket_status = 4 THEN '".Yii::t("app","new")."'
	            	WHEN ticket_status = 3 THEN '".Yii::t("app","open")."'
	            	WHEN ticket_status = 2 THEN '".Yii::t("app","progress")."'
	            	WHEN ticket_status = 1 THEN '".Yii::t("app","finish")."'
	            	WHEN ticket_status = 0 AND ticket_status_helpdesk = 1 THEN '".Yii::t("app","closed")." (50%) '
	            	WHEN ticket_status = 0 AND ticket_status_helpdesk = 0 THEN '".Yii::t("app","closed")." (100%) '		
		        END ticket_status_string,ticket_helpdesk
		        FROM ticket t
		        LEFT JOIN helpdesk h on h.employee_id = t.ticket_helpdesk
		        LEFT JOIN employee hh on hh.employee_id = h.employee_id		
		        INNER JOIN employee e on e.employee_id = t.employee_id
		        WHERE e.employee_id > 0 ";
        if($employee_id) $sql.= " AND t.employee_id=".$employee_id;
        if($status) $sql.= " AND ticket_status=".$status;
        if($helpdesk) $sql.= " AND ticket_helpdesk=".$helpdesk;
        
        if ($this->load($params) && $this->ticket_status != "")
            $sql.=" AND ticket_status = ".$this->ticket_status; 
        
        if ($this->load($params) && $this->ticket_from_date)
            $sql.=" AND ticket_date >= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."'";
        
        if ($this->load($params) && $this->ticket_to_date)
            $sql.=" AND ticket_date <= '".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."'";
        */
        
        //$sql.=" ORDER BY ticket_cdate ASC ";    
        //$query = Ticket::findBySql($sql);
       	$query = Ticket::find()
       	->from("ticket t")
       	->join('inner join', 'employee e','e.employee_id = t.employee_id')
       	->join('left join', 'employee h','h.employee_id = t.ticket_helpdesk')
       	->select(["ticket_id","DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date","ticket_subject","ticket_usercomment",
       		"CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name",
       		"CONCAT(h.EmployeeFirstName,' ',h.EmployeeMiddleName,' ',h.EmployeeLastName) as helpdesk_name",	
       		"CASE WHEN ticket_status = 4 THEN '".Yii::t("app","new")."'
	         WHEN ticket_status = 3 THEN '".Yii::t("app","open")."'
	         WHEN ticket_status = 2 THEN '".Yii::t("app","progress")."'
	         WHEN ticket_status = 1 THEN '".Yii::t("app","finish")."'
	         WHEN ticket_status = 0 AND ticket_status_helpdesk = 1 THEN '".Yii::t("app","closed")." (50%) '
	         WHEN ticket_status = 0 AND ticket_status_helpdesk = 0 THEN '".Yii::t("app","closed")." (100%) '		
		     END ticket_status_string"	
       	]);
       	
       	//request GET Non Params employee id
       	if (!empty($employee_id)) {
       		$query = $query->where('t.employee_id = :id', [':id' => $employee_id]);
       	}
       	//request GET Non Params status
       	if (!empty($status)) {
       		$query = $query->where('t.ticket_status = :status', [':status' => $status]);
       	}
       	//request GET Non Params helpdesk
       	if (!empty($helpdesk)) {
       		$query = $query->where('t.ticket_helpdesk = :helpdesk', [':helpdesk' => $helpdesk]);
       	}
       	
        $countQuery = count($query->all());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $countQuery,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page'],
            ],
     
        ]);
        
        $dataProvider->setSort([
        	'defaultOrder' => ['ticket_date'=>SORT_DESC],
        	'attributes' => [
        		'ticket_id' => [
        			'asc' => ['ticket_id' => SORT_ASC],
        			'desc' => ['ticket_id' => SORT_DESC],
        			//'default' => SORT_DESC
        		],
        		'ticket_date' => [
       				'asc' => ['ticket_date' => SORT_ASC],
      				'desc' => ['ticket_date' => SORT_DESC],
       				//'default' => SORT_DESC
       			],
        		'ticket_subject' => [
        			'asc' => ['ticket_subject' => SORT_ASC],
        			'desc' => ['ticket_subject' => SORT_DESC],
        		],
        		'employee_name' => [
        			'asc' => ['employee_name' => SORT_ASC],
        			'desc' => ['employee_name' => SORT_DESC],
        		],
        		'ticket_status_string' => [
        			'asc' => ['ticket_status_string' => SORT_ASC],
        			'desc' => ['ticket_status_string' => SORT_DESC],
        		],
        		'helpdesk_name' => [
        			'asc' => ['helpdesk_name' => SORT_ASC],
        			'desc' => ['helpdesk_name' => SORT_DESC],
        		],
        		'ticket_usercomment' => [
        			'asc' => ['ticket_usercomment' => SORT_ASC],
        			'desc' => ['ticket_usercomment' => SORT_DESC],
        		],
        	],
        ]);	
        
        if (!($this->load($params) && $this->validate())) {
        	return $dataProvider;
        }
        
        if (!empty($this->ticket_from_date))
        	$query->andFilterWhere(['>=', 'ticket_date', preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)]);
        if (!empty($this->ticket_to_date))
        	$query->andFilterWhere(['<=', 'ticket_date', preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)]);        	
        if(!empty($this->ticket_status))
        	$query->andFilterWhere(['=', 'ticket_status', $this->ticket_status]);
     
        return $dataProvider;
    }
    
    public function getAllRequestDataProvider($params) {
    	$status = 0;
    	$query = Ticket::find()
    	->from("ticket t")
    	->join('inner join', 'employee e','e.employee_id = t.employee_id')
    	->join('left join', 'employee h','h.employee_id = t.ticket_helpdesk')
    	->where('t.ticket_status > :status', [':status' => $status])
    	->select(["ticket_id","DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date","ticket_subject","ticket_usercomment",
    			"CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name",
    			"CONCAT(h.EmployeeFirstName,' ',h.EmployeeMiddleName,' ',h.EmployeeLastName) as helpdesk_name",
    			"CASE WHEN ticket_status = 4 THEN '".Yii::t("app","new")."'
	         WHEN ticket_status = 3 THEN '".Yii::t("app","open")."'
	         WHEN ticket_status = 2 THEN '".Yii::t("app","progress")."'
	         WHEN ticket_status = 1 THEN '".Yii::t("app","finish")."'
	         WHEN ticket_status = 0 AND ticket_status_helpdesk = 1 THEN '".Yii::t("app","closed")." (50%) '
	         WHEN ticket_status = 0 AND ticket_status_helpdesk = 0 THEN '".Yii::t("app","closed")." (100%) '
		     END ticket_status_string"
    	]);
    	
    	$countQuery = count($query->all());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $countQuery,
            'pagination' =>[
                'pageSize' => Yii::$app->params['per_page'],
            ],
     
        ]);
        
        $dataProvider->setSort([
        	'defaultOrder' => ['ticket_date'=>SORT_DESC],
        	'attributes' => [
        		'ticket_id' => [
        			'asc' => ['ticket_id' => SORT_ASC],
        			'desc' => ['ticket_id' => SORT_DESC],
        			//'default' => SORT_DESC
        		],
        		'ticket_date' => [
       				'asc' => ['ticket_date' => SORT_ASC],
      				'desc' => ['ticket_date' => SORT_DESC],
       				//'default' => SORT_DESC
       			],
        		'ticket_subject' => [
        			'asc' => ['ticket_subject' => SORT_ASC],
        			'desc' => ['ticket_subject' => SORT_DESC],
        		],
        		'employee_name' => [
        			'asc' => ['employee_name' => SORT_ASC],
        			'desc' => ['employee_name' => SORT_DESC],
        		],
        		'ticket_status_string' => [
        			'asc' => ['ticket_status_string' => SORT_ASC],
        			'desc' => ['ticket_status_string' => SORT_DESC],
        		],
        		'helpdesk_name' => [
        			'asc' => ['helpdesk_name' => SORT_ASC],
        			'desc' => ['helpdesk_name' => SORT_DESC],
        		],
        		'ticket_usercomment' => [
        			'asc' => ['ticket_usercomment' => SORT_ASC],
        			'desc' => ['ticket_usercomment' => SORT_DESC],
        		],
        	],
        ]);	
    	
    	
    	return $dataProvider;
    }
    
    public function getTicketSingleData($id){
        $sql=" SELECT ticket_id,DATE_FORMAT(ticket_date,'%d/%m/%Y') as ticket_date,ticket_subject,
        CONCAT(hh.EmployeeFirstName,' ',hh.EmployeeMiddleName,' ',hh.EmployeeLastName) as helpdesk_name,ticket_usercomment,
        CONCAT(e.EmployeeFirstName,' ',e.EmployeeMiddleName,' ',e.EmployeeLastName) as employee_name,
        CASE ticket_status
            WHEN 4 THEN '".Yii::t("app","new")."'
	        WHEN 3 THEN '".Yii::t("app","open")."'
	        WHEN 2 THEN '".Yii::t("app","progress")."'
	        WHEN 1 THEN '".Yii::t("app","finish")."'
	        WHEN 0 THEN '".Yii::t("app","closed")."'
        END ticket_status_string,ticket_status,ticket_helpdesk,ticket_note,ticket_type,ticket_handling
        FROM ticket t
        LEFT JOIN helpdesk h on h.employee_id = t.ticket_helpdesk
        LEFT JOIN employee hh on hh.employee_id = h.employee_id
        INNER JOIN employee e on e.employee_id = t.employee_id
        WHERE ticket_id=".$id;
        $query = Ticket::findBySql($sql)->one();
        return $query;
    }
    
    public function getSetHelpdesk($id){
        $model = new Ticket(['scenario'=>'set_helpdesk']);
        if($this->validate()){
            $model = $model->findOne($id);
            $model->ticket_helpdesk = $this->ticket_helpdesk;
            $model->ticket_handling = $this->ticket_handling;
            $model->ticket_status = $this->open;
            $model->ticket_usercomment = Yii::t('app/message','msg ticket set helpdesk');
            $model->ticket_uid = Yii::$app->user->getId();
            $model->ticket_udate = date('Y-m-d H:i:s');
            $model->update();
            
            //comment for update
            $relation = new \app\models\TicketLog();
            $relation->ticket_id = $id;
            $relation->employee_id = Yii::$app->user->getId();
            $relation->to_employee_id = $this->ticket_helpdesk;
            $relation->log_date = date('Y-m-d H:i:s');
            $relation->log_desc = $this->ticket_handling;
            $relation->insert();
            
            return true;
        }
    }
    
    public static function getHelpdeskNewTicketData($status){
        $ticket = new Ticket();
        $query = $ticket->find()
        ->select(['*'])
        ->join('inner join','employee','employee.employee_id=ticket.employee_id')
        ->where(['ticket_status'=>$status])
        ->all();
        return $query;
    }
    
    public static function getHelpdeskTicketData($status){
        $ticket = new Ticket();
        $query = $ticket->find()
        ->select(['*'])
        ->join('inner join','employee','employee.employee_id=ticket.employee_id')
        ->where(['ticket_status'=>$status,'ticket_helpdesk'=>Yii::$app->user->getId()])
        ->all();
        return $query;
    }
    
    public function getTotalTicketByHelpdesk($params) {
    	$sql = "
    			SELECT (SELECT COUNT(ticket_id) FROM ticket t WHERE t.ticket_helpdesk = h.`employee_id` 
		    			AND (ticket_date >='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."' AND  ticket_date <='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."' )		
		    			) as total
		    	FROM helpdesk h
		    	INNER JOIN employee e ON e.`employee_id`= h.`employee_id`				
		    	WHERE h.`role_id` > 1
		    	ORDER BY CONCAT(e.EmployeeFirstName) ASC								
    	";
    	$rows = self::findBySql($sql)->all();
    	$data = [];
    	 
    	foreach($rows as $key => $row){
    		$data[] = (int) $row->total;
    	}
    	return $data;
    }
    
    public function getClosedTicketByHelpdesk($params) {
    	$sql = "
    			SELECT (SELECT COUNT(ticket_id) FROM ticket t WHERE t.ticket_helpdesk = h.`employee_id` and ticket_status = 0 and ticket_status_helpdesk = 0 
		    			AND (ticket_date >='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."' AND  ticket_date <='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."' )
		    			) as total
		    	FROM helpdesk h
		    	INNER JOIN employee e ON e.`employee_id`= h.`employee_id`
		    	WHERE h.`role_id` > 1
		    	ORDER BY CONCAT(e.EmployeeFirstName) ASC
    	";
    	$rows = self::findBySql($sql)->all();
    	$data = [];
    
    	foreach($rows as $key => $row){
    		$data[] = (int) $row->total;
    	}
    	return $data;
    }
    
    public function getTotalTicketByCategory($params) {
    	$sql = "
    			SELECT (SELECT COUNT(ticket.ticket_id) FROM ticket WHERE ticket.ticket_category_id = ticket_categories.id 
		    			AND ticket_date >='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."' 
		    			AND  ticket_date <='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."' 
		    			) as total
		    	FROM ticket_categories
		    	order by (select count(ticket_id) from ticket 
						where ticket.ticket_category_id = ticket_categories.id
						AND ticket.ticket_date >='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_from_date)."' 
						AND ticket.ticket_date <='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$this->ticket_to_date)."'
				) desc	
    	";
    	$rows = self::findBySql($sql)->all();
    	$data = [];
    
    	foreach($rows as $key => $row){
    		$data[] = (int) $row->total;
    	}
    	return $data;
    }
    
    public function getAllUserWaiting() {
    	$sql = "select ticket_id,ticket_subject,e.EmployeeEmail,h.EmployeeEmail as helpdesk_email
    			from ticket t 
    			inner join employee h on h.employee_id = t.ticket_helpdesk
    			inner join employee e on e.employee_id = t.employee_id
    			where t.ticket_status = 2
    			and (to_days(now()) - to_days(t.ticket_udate)) > ".Yii::$app->params['closed_ticket_expired']."
    	";
    	return self::findBySql($sql)->all();
    }
}