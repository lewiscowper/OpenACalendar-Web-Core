<?php


namespace repositories;

use models\SiteModel;
use models\UserAccountModel;
use models\UserGroupModel;
use Symfony\Component\Config\Definition\Exception\Exception;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class UserGroupRepository {


	public function createForSite(SiteModel $site, UserGroupModel $userGroupModel, UserAccountModel $userAccountModel=null, $initialUserPermissions=array(), $initialUsers=array()) {
		global $DB;

		$inTransaction = $DB->inTransaction();

		$statInsertUserGroupInfo = $DB->prepare("INSERT INTO user_group_information (title,description,is_in_index,is_includes_anonymous,is_includes_users,is_includes_verified_users,created_at) ".
			"VALUES (:title,:description,'0',:is_includes_anonymous,:is_includes_users,:is_includes_verified_users,:created_at) RETURNING id");
		$statInsertUserGroupHistory = $DB->prepare("INSERT INTO user_group_history (user_group_id,title,description,is_in_index,is_includes_anonymous,is_includes_users,is_includes_verified_users,created_at,user_account_id) ".
			"VALUES (:user_group_id,:title,:description,'0',:is_includes_anonymous,:is_includes_users,:is_includes_verified_users,:created_at,:user_account_id)");

		$statInsertUserGroupInSite = $DB->prepare("INSERT INTO user_group_in_site (user_group_id,site_id,added_at,added_by_user_account_id) ".
			"VALUES (:user_group_id,:site_id,:added_at,:added_by_user_account_id)");
		$statInsertUserInUserGroup = $DB->prepare("INSERT INTO user_in_user_group (user_group_id, user_account_id, added_at,added_by_user_account_id) ".
			"VALUES (:user_group_id, :user_account_id, :added_at, :added_by_user_account_id)");
		$statInsertPermissionInUserGroup = $DB->prepare("INSERT INTO permission_in_user_group (user_group_id,extension_id, permission_key,added_at,added_by_user_account_id) ".
			"VALUES (:user_group_id,:extension_id, :permission_key,:added_at,:added_by_user_account_id)");

		try {
			if (!$inTransaction) $DB->beginTransaction();

			// User Group
			$statInsertUserGroupInfo->execute(array(
				"title"=>$userGroupModel->getTitle(),
				"description"=>$userGroupModel->getDescription(),
				"is_includes_anonymous"=> $userGroupModel->getIsIncludesAnonymous() ? "1" : "0",
				"is_includes_users"=> $userGroupModel->getIsIncludesUsers() ? "1" : "0",
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
			));
			$data = $statInsertUserGroupInfo->fetch();
			$userGroupModel->setId($data['id']);

			$statInsertUserGroupHistory->execute(array(
				"user_group_id"=>$userGroupModel->getId(),
				"title"=>$userGroupModel->getTitle(),
				"description"=>$userGroupModel->getDescription(),
				"is_includes_anonymous"=> $userGroupModel->getIsIncludesAnonymous() ? "1" : "0",
				"is_includes_users"=> $userGroupModel->getIsIncludesUsers() ? "1" : "0",
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
				"user_account_id"=>($userAccountModel ? $userAccountModel->getId() : null),
			));

			$statInsertUserGroupInSite->execute(array(
				"user_group_id"=>$userGroupModel->getId(),
				"site_id"=>$site->getId(),
				"added_at"=>\TimeSource::getFormattedForDataBase(),
				"added_by_user_account_id"=>($userAccountModel ? $userAccountModel->getId() : null),
			));

			// Permissions
			foreach($initialUserPermissions as $initialUserPermission) {
				if (is_array($initialUserPermission) && count($initialUserPermission) == 2) {
					$statInsertPermissionInUserGroup->execute(array(
						"user_group_id"=>$userGroupModel->getId(),
						"extension_id"=>$initialUserPermission[0],
						"permission_key"=>$initialUserPermission[1],
						"added_at"=>\TimeSource::getFormattedForDataBase(),
						"added_by_user_account_id"=>($userAccountModel ? $userAccountModel->getId() : null),
					));
				}
			}

			// Users
			foreach($initialUsers as $initialUser) {
				$statInsertUserInUserGroup->execute(array(
						"user_group_id"=>$userGroupModel->getId(),
						"user_account_id"=>$initialUser->getId(),
						"added_at"=>\TimeSource::getFormattedForDataBase(),
						"added_by_user_account_id"=>($userAccountModel ? $userAccountModel->getId() : null),
				));
			}

			if (!$inTransaction) $DB->commit();
		} catch (Exception $e) {
			if (!$inTransaction) $DB->rollBack();
		}

	}

	public function createForIndex(UserGroupModel $userGroupModel, UserAccountModel $userAccountModel=null) {
		global $DB;

		$inTransaction = $DB->inTransaction();

		$statInsertUserGroupInfo = $DB->prepare("INSERT INTO user_group_information (title,description,is_in_index,is_includes_anonymous,is_includes_users,is_includes_verified_users,created_at) ".
			"VALUES (:title,:description,'1',:is_includes_anonymous,:is_includes_users,:is_includes_verified_users,:created_at) RETURNING id");
		$statInsertUserGroupHistory = $DB->prepare("INSERT INTO user_group_history (user_group_id,title,description,is_in_index,is_includes_anonymous,is_includes_users,is_includes_verified_users,created_at,user_account_id) ".
			"VALUES (:user_group_id,:title,:description,'1',:is_includes_anonymous,:is_includes_users,:is_includes_verified_users,:created_at,:user_account_id)");

		try {
			if (!$inTransaction) $DB->beginTransaction();

			// User Group
			$statInsertUserGroupInfo->execute(array(
				"title"=>$userGroupModel->getTitle(),
				"description"=>$userGroupModel->getDescription(),
				"is_includes_anonymous"=> $userGroupModel->getIsIncludesAnonymous() ? "1" : "0",
				"is_includes_users"=> $userGroupModel->getIsIncludesUsers() ? "1" : "0",
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
			));
			$data = $statInsertUserGroupInfo->fetch();
			$userGroupModel->setId($data['id']);

			$statInsertUserGroupHistory->execute(array(
				"user_group_id"=>$userGroupModel->getId(),
				"title"=>$userGroupModel->getTitle(),
				"description"=>$userGroupModel->getDescription(),
				"is_includes_anonymous"=> $userGroupModel->getIsIncludesAnonymous() ? "1" : "0",
				"is_includes_users"=> $userGroupModel->getIsIncludesUsers() ? "1" : "0",
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
				"user_account_id"=>($userAccountModel ? $userAccountModel->getId() : null),
			));

			if (!$inTransaction) $DB->commit();
		} catch (Exception $e) {
			if (!$inTransaction) $DB->rollBack();
		}

	}

	public function addUserToGroup(UserAccountModel $userAccountModel, UserGroupModel $userGroupModel, UserAccountModel $currentUser = null) {
		global $DB;

		$inTransaction = $DB->inTransaction();

		$statInsertUserInUserGroup = $DB->prepare("INSERT INTO user_in_user_group (user_group_id, user_account_id, added_at, added_by_user_account_id) ".
			"VALUES (:user_group_id, :user_account_id, :added_at, :added_by_user_account_id)");

		try {
			if (!$inTransaction) $DB->beginTransaction();

			// TODO check already in

			$statInsertUserInUserGroup->execute(array(
						"user_group_id"=>$userGroupModel->getId(),
						"user_account_id"=>$userAccountModel->getId(),
						"added_at"=>\TimeSource::getFormattedForDataBase(),
						"added_by_user_account_id"=>($currentUser ? $currentUser->getId() : null),
				));


			if (!$inTransaction) $DB->commit();
		} catch (Exception $e) {
			if (!$inTransaction) $DB->rollBack();
		}

	}

	public function removeUserFromGroup(UserAccountModel $userAccountModel, UserGroupModel $userGroupModel, UserAccountModel $currentUser = null) {
		global $DB;

		$stat = $DB->prepare("UPDATE user_in_user_group SET removed_at=:removed_at, removed_by_user_account_id=:removed_by_user_account_id WHERE ".
			"user_group_id=:user_group_id AND user_account_id=:user_account_id AND removed_at IS NULL");

		$stat->execute(array(
			"user_group_id"=>$userGroupModel->getId(),
			"user_account_id"=>$userAccountModel->getId(),
			"removed_at"=>\TimeSource::getFormattedForDataBase(),
			"removed_by_user_account_id"=>($currentUser ? $currentUser->getId() : null),
		));

	}

	
	public function loadById($id) {
		global $DB;
		$stat = $DB->prepare("SELECT user_group_information.* FROM user_group_information WHERE id = :id");
		$stat->execute(array( 'id'=>$id, ));
		if ($stat->rowCount() > 0) {
			$ugm = new UserGroupModel();
			$ugm->setFromDataBaseRow($stat->fetch());
			return $ugm;
		}
	}


	public function loadByIdInSite($id, SiteModel $siteModel) {
		global $DB;
		$stat = $DB->prepare("SELECT user_group_information.* FROM user_group_information ".
			" JOIN user_group_in_site ON user_group_in_site.user_group_id = user_group_information.id ".
			  " AND user_group_in_site.site_id = :site_id AND user_group_in_site.removed_at IS NULL ".
			" WHERE id = :id");
		$stat->execute(array( 'id'=>$id, 'site_id'=>$siteModel->getId()));
		if ($stat->rowCount() > 0) {
			$ugm = new UserGroupModel();
			$ugm->setFromDataBaseRow($stat->fetch());
			return $ugm;
		}
	}


}

