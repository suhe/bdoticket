<?php
namespace app\commands;
use Yii;
use app\models\Ticket;
use app\models\TicketLog;
use yii\console\Controller;

class AppMailController extends Controller  {
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-mail/new-ticket
	 */
	public function actionNewTicket() {
		$model = new Ticket();
		$tickets = $model->getAllData(['ticket_status' => 4]);
		if ($tickets) {
			$mail = [ ];
			foreach ( $tickets as $ticket) {
				$mail[]  = Yii::$app->mailer->compose('ticket-new',['data' => $ticket])
				->setFrom(\Yii::$app->params['mail_user'])
				->setTo($ticket->EmployeeEmail)
				->setCc(array('hendarsyahss@gmail.com'))
				->setSubject(Yii::t('app','new ticket'));
			}
			//send multiple email
			Yii::$app->mailer->sendMultiple($mail);
		}
		//return false;
	}
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-mail/new-ticket
	 */
	public function actionOpenTicket() {
		$model = new Ticket();
		$tickets = $model->getAllData(['ticket_status' => 3]);
		if ($tickets) {
			$mail = [ ];
			foreach ( $tickets as $ticket) {
				$mail[]  = Yii::$app->mailer->compose('ticket-open',['data' => $ticket])
				->setFrom(\Yii::$app->params['mail_user'])
				->setTo($ticket->EmployeeEmail)
				->setCc(array('hendarsyahss@gmail.com'))
				->setSubject(Yii::t('app','new ticket'));
			}
			//send multiple email
			Yii::$app->mailer->sendMultiple($mail);
		}
		//return false;
	}
	
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-mail/reply-ticket
	 */
	public function actionReplyTicket() {
		$model = new TicketLog();
		$tickets = $model->getAllNewMessage();
		if ($tickets) {
			$mail = [ ];
			foreach ( $tickets as $ticket) {
				$mail[]  = Yii::$app->mailer->compose('ticket-reply',['data' => $ticket])
				->setFrom(\Yii::$app->params['mail_user'])
				->setTo(array($ticket->employee_to_email))
				->setCc(array($ticket->employee_from_email,'hendarsyahss@gmail.com'))
				->setSubject(Yii::t('app','reply ticket').' #'.$ticket->ticket_id);
			}
			//send multiple email
			Yii::$app->mailer->sendMultiple($mail);
		}
		//return false;
	}
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-mail/new-ticket
	 */
	public function actionUserClosedTicket() {
		$model = new Ticket();
		$tickets = $model->getAllData(['ticket_status' => 0 , 'ticket_status_helpdesk' => 1]);
		if ($tickets) {
			$mail = [ ];
			foreach ( $tickets as $ticket) {
				$mail[]  = Yii::$app->mailer->compose('ticket-user-closed',['data' => $ticket])
				->setFrom(\Yii::$app->params['mail_user'])
				->setTo($ticket->helpdesk_email)
				->setCc($ticket->EmployeeEmail)
				->setSubject(Yii::t('app','closed user ticket'));
			}
			//send multiple email
			Yii::$app->mailer->sendMultiple($mail);
		}
		//return false;
	}
	
	/**
	 * This command echoes what you have email.
	 * @param email
	 * //cron job php -q /home/k0455101/public_html/devleave/yii app-leave/email
	 * php -q /home/k0455101/public_html/devleave/yii app-mail/new-ticket
	 */
	public function actionClosedTicket() {
		$model = new Ticket();
		$tickets = $model->getAllData(['ticket_status' => 0 , 'ticket_status_helpdesk' => 0]);
		if ($tickets) {
			$mail = [ ];
			foreach ( $tickets as $ticket) {
				$mail[]  = Yii::$app->mailer->compose('ticket-closed',['data' => $ticket])
				->setFrom(\Yii::$app->params['mail_user'])
				->setTo($ticket->EmployeeEmail)
				->setCc($ticket->helpdesk_email)
				->setSubject(Yii::t('app','closed ticket'));
			}
			//send multiple email
			Yii::$app->mailer->sendMultiple($mail);
		}
		//return false;
	}
	
}