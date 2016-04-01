<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Employee extends ActiveRecord implements IdentityInterface {
    
    public $passtext;
    public $remember_me;
    public $EmployeeName;
    public $_user=false;
    
    public static function tableName(){
        return 'employee';
    }
    
    public function rules(){
        return [
            [['EmployeeID','passtext'],'string'],
            [['EmployeeID'],'required','message'=>Yii::t('app/message','msg identity not empty'),'on'=>'login'],
            [['passtext'],'required','message'=>Yii::t('app/message','msg password not empty'),'on'=>'login'],
            [['passtext'],'validatePassword','on'=>'login'],
            [['EmployeeFirstName'],'required','message'=>Yii::t('app/message','msg first name not empty'),'on'=>'update_account'],
            [['EmployeeMiddleName'],'safe','on'=>'update_account'],
            [['EmployeeLastName'],'safe','on'=>'update_account'],
            [['EmployeeEmail'],'required','message'=>Yii::t('app/message','msg email not empty'),'on'=>'update_account'],
            [['EmployeeEmail'],'email','message'=>Yii::t('app/message','msg email required'),'on'=>'update_account'],
        ];
    }
    
    public function attributeLabels(){
        return [
            'EmployeeFirstName' => Yii::t('app','first name'),
            'EmployeeMiddleName' => Yii::t('app','middle name'),
            'EmployeeLastName' => Yii::t('app','last name'),
            'EmployeeEmail' => Yii::t('app','email'),
        ];
    }

    /**
     * Relations with User
     */
    public function getUser(){
        return $this->hasOne(User::className(),['employee_id'=>'employee_id']);
    }
    
    /**
     * Relations with Department
     */
    public function getDepartment(){
        return $this->hasOne(Department::className(),['department_id'=>'department_id']);
    }
    
    /**
     * abstract model identity interface $app->user->login
     * findIdentity , findIdentityByAccessToken , getId , getAuthKey , validateAuthKey
    */
    
    public static function findIdentity($id){
        return static::findOne(['employee_id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public function getId(){
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey(){
        return $this->EmployeeTitle;
    }
    
    public function validateAuthKey($authKey){
        return $true;
    }
    
    /** End Of Identity
    */
    public function getLogin(){
        if($this->validate())
            return Yii::$app->user->login($this->getUserSingleData(),$this->remember_me ? 3600 * 24 * 30 : 0);
        else return false;
    }
    
    public function getUserSingleData(){
        if ($this->_user === false) {
            $this->_user = Employee::findByIdentity($this->EmployeeID,$this->passtext);
        }
        return $this->_user;
    }
    
    public static function findByIdentity($identity,$password){
        return Employee::find()
        ->select(["E.employee_id,E.EmployeeID,CONCAT(E.EmployeeFirstName,' ',E.EmployeeMiddleName,' ',E.EmployeeLastName) as EmployeeName"])
        ->from('employee E')
        ->join('inner join','sys_user su','su.employee_id=E.employee_id')
        ->where(['E.EmployeeID' => $identity,'su.passtext'=>$password])
        ->one();
    }
    
    
    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()) {
            $user = $this->findByIdentity($this->EmployeeID,$this->passtext);
            if (!$user) {
                $this->addError($attribute,Yii::t('app/message','msg incorrect username or password'));
            }
        }
    }
    
    public static function getEmployeeDropdownList(){
        $arr = [];
        $data = Employee::find()
        ->from('employee e')
        ->join('inner join','sys_user su','su.employee_id=e.employee_id')
        ->where(['su.user_active'=>1])
        ->orderBy('e.EmployeeFirstname,e.EmployeeMiddleName,e.EmployeeLastName')
        ->all();
        
        foreach($data as $row){
            $arr[$row->employee_id] = $row->EmployeeFirstName.' '.$row->EmployeeMiddleName.' '.$row->EmployeeLastName;
        }
        return $arr;
    }
    
    public function getUpdateProfile($id){
        if($this->validate()){
            $model = new Employee();
            $model = $model->findOne($id);
            $model->EmployeeFirstName = $this->EmployeeFirstName;
            $model->EmployeeMiddleName = $this->EmployeeMiddleName;
            $model->EmployeeLastName = $this->EmployeeLastName;
            $model->EmployeeEmail = $this->EmployeeEmail;
            $model->update();
            return true;
        }
    }
    
    public static function getEmployeeEmail($ticket_id){
        $query = \app\models\Ticket::findOne($ticket_id);
        if(!Yii::$app->session->get('helpdesk')){
            $user_id = $query->ticket_helpdesk;
        } else {
            $user_id = $query->employee_id;
        }
        
        $query2 = Employee::findOne($user_id);
        return $query2->EmployeeEmail;
    }
    
    public static function getEmployeeEmailById($id){
        $query = Employee::findOne($id);
        $email = $query->EmployeeEmail;
        if(!$email) return 'ssuhendar@bdo.co.id';
        return $email;
    }
    
}