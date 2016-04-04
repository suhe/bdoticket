<?php
namespace app\components;
use Yii;
use yii\filters\AccessRule;
use app\models\Helpdesk;

class Role extends AccessRule {

	/**
	 * @inheritdoc
	 */
	protected function matchRole($user) {
		if (empty($this->roles)) {
			return true;
		}
		
		foreach ($this->roles as $role) {
			if ($role === '?') {
				if (Yii::$app->user->isGuest) {
					return true;
				}
			} elseif ($role === '@') {
				if (!Yii::$app->user->isGuest) {
					return true;
				}
			// Check if the user is logged in, and the roles match
			} elseif (!$user->getIsGuest() && $role === Helpdesk::isUserType()) {
				return true;
			}
		}

		return false;
	}
}