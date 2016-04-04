<?php
namespace app\models;
use yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class TicketCategory extends ActiveRecord {
	public static function tableName(){
		return 'ticket_categories';
	}
	
	public static function lists($label,$value) {
		$lists = array();
		$query = self::find()->all();
		if($query) {
			foreach($query as $key => $row) {
				$lists[$row->$value] = $row->$label;
			}
		}
		
		return $lists;
	}
	
	public static function getLists($params = array()) {
		$sql = "
			select name from ticket_categories
			order by (select count(ticket_id) from ticket 
						where ticket.ticket_category_id = ticket_categories.id
						AND ticket.ticket_date >='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$params['ticket_from_date'])."' 
						AND ticket.ticket_date <='".preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',$params['ticket_to_date'])."'
				) desc	
		";
		
		$query = self::findBySql($sql)->all();
		
		$arr=[];
		foreach($query as $key => $row){
			$arr[] = $row->name;
		}
		return $arr;
	}
}

