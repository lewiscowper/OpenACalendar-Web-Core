<?php

namespace repositories;

use models\UserAccountModel;
use models\UserGroupModel;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */

class UserPermissionsRepository {

	public function getPermissionsForUserGroup(UserGroupModel $userGroupModel) {
		global $DB, $app;

		$stat = $DB->prepare("SELECT permission_in_user_group.* FROM permission_in_user_group ".
			"WHERE permission_in_user_group.user_group_id = :user_group_id AND permission_in_user_group.removed_at IS NULL");
		$stat->execute(array(
			'user_group_id'=>$userGroupModel->getId(),
		));
		$permissions = array();
		while($data = $stat->fetch()) {
			$ext = $app['extensions']->getExtensionById($data['extension_id']);
			if ($ext) {
				$permissions[] = $ext->getUserPermission($data['permission_key']);
			}
		}
		return $permissions;
	}


	public function getPermissionsForUserInIndex(UserAccountModel $userAccountModel = null) {
		global $DB, $app;

		if ($userAccountModel) {

			$stat = $DB->prepare("SELECT permission_in_user_group.* FROM permission_in_user_group ".
				" JOIN user_group_information ON user_group_information.id = permission_in_user_group.user_group_id AND user_group_information.is_deleted = '0' AND user_group_information.is_in_index = '1' ".
				" LEFT JOIN user_in_user_group ON user_in_user_group.user_group_id = user_group_information.id AND user_in_user_group.removed_at IS NULL ".
				" WHERE permission_in_user_group.removed_at IS NULL AND ".
				" ( user_in_user_group.user_account_id = :user_account_id OR  user_group_information.is_includes_users = '1' ".($userAccountModel->getIsEmailVerified() ? " OR user_group_information.is_includes_verified_users = '1'  " : "")." ) ");
			$stat->execute(array(
				'user_account_id'=>$userAccountModel->getId(),
			));

		} else {


			$stat = $DB->prepare("SELECT permission_in_user_group.* FROM permission_in_user_group ".
				" JOIN user_group_information ON user_group_information.id = permission_in_user_group.user_group_id AND user_group_information.is_deleted = '0' AND user_group_information.is_in_index = '1' ".
				" WHERE permission_in_user_group.removed_at IS NULL AND user_group_information.is_includes_anonymous = '1' ");
			$stat->execute(array());

		}

		$permissions = array();
		while($data = $stat->fetch()) {
			$ext = $app['extensions']->getExtensionById($data['extension_id']);
			if ($ext) {
				$permissions[] = $ext->getUserPermission($data['permission_key']);
			}
		}
		return new \UserPermissionsList($permissions, $userAccountModel);
	}


}

