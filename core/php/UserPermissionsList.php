<?php


/**
 *
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class UserPermissionsList {


	protected $permissions = array();

	protected $has_user = false;
	protected $has_user_verified = false;
	protected $has_user_editor = false;
	protected $has_user_system_administrator = false;

	function __construct($permissions, \models\UserAccountModel $userAccountModel = null)
	{
		$this->permissions = $permissions;
		if ($userAccountModel) {
			$this->has_user = true;
			$this->has_user_editor = $userAccountModel->getIsEditor();
			$this->has_user_verified = $userAccountModel->getIsEmailVerified();
			$this->has_user_system_administrator = $userAccountModel->getIsSystemAdmin();
		}
	}

	function hasPermission($extId, $key) {
		foreach($this->permissions as $permission) {
			if ($permission->getUserPermissionExtensionID() == $extId && $permission->getUserPermissionKey() == $key) {
				return true;
			}
		}
		return false;
	}
}

