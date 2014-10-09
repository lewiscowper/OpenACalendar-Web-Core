<?php


namespace repositories;

use models\SiteModel;
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


	public function createForSite(SiteModel $site, UserGroupModel $userGroupModel, $initialUserPermissions=array(), $initialUsers=array()) {
		global $DB;

		$inTransaction = $DB->inTransaction();

		// TODO missing fields from user_group_information and user_group_history

		$statInsertUserGroupInfo = $DB->prepare("INSERT INTO user_group_information (title,is_in_index,is_includes_verified_users,created_at) ".
			"VALUES (:title,'0',:is_includes_verified_users,:created_at) RETURNING id");
		$statInsertUserGroupHistory = $DB->prepare("INSERT INTO user_group_history (user_group_id,title,is_in_index,is_includes_verified_users,created_at) ".
			"VALUES (:user_group_id,:title,'0',:is_includes_verified_users,:created_at)");

		$statInsertUserGroupInSite = $DB->prepare("INSERT INTO user_group_in_site (user_group_id,site_id,added_at) ".
			"VALUES (:user_group_id,:site_id,:added_at)");
		$statInsertUserInUserGroup = $DB->prepare("INSERT INTO user_in_user_group (user_group_id, user_account_id, added_at) ".
			"VALUES (:user_group_id, :user_account_id, :added_at)");
		$statInsertPermissionInUserGroup = $DB->prepare("INSERT INTO permission_in_user_group (user_group_id,extension_id, permission_key,added_at) ".
			"VALUES (:user_group_id,:extension_id, :permission_key,:added_at)");

		try {
			if (!$inTransaction) $DB->beginTransaction();

			// User Group
			$statInsertUserGroupInfo->execute(array(
				"title"=>$userGroupModel->getTitle(),
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
			));
			$data = $statInsertUserGroupInfo->fetch();
			$userGroupModel->setId($data['id']);

			$statInsertUserGroupHistory->execute(array(
				"user_group_id"=>$userGroupModel->getId(),
				"title"=>$userGroupModel->getTitle(),
				"is_includes_verified_users"=> $userGroupModel->getIsIncludesVerifiedUsers() ? "1" : "0",
				"created_at"=>\TimeSource::getFormattedForDataBase(),
			));

			$statInsertUserGroupInSite->execute(array(
				"user_group_id"=>$userGroupModel->getId(),
				"site_id"=>$site->getId(),
				"added_at"=>\TimeSource::getFormattedForDataBase(),
			));

			// Permissions
			foreach($initialUserPermissions as $initialUserPermission) {
				if (is_array($initialUserPermission) && count($initialUserPermission) == 2) {
					$statInsertPermissionInUserGroup->execute(array(
						"user_group_id"=>$userGroupModel->getId(),
						"extension_id"=>$initialUserPermission[0],
						"permission_key"=>$initialUserPermission[1],
						"added_at"=>\TimeSource::getFormattedForDataBase(),
					));
				}
			}

			// Users
			foreach($initialUsers as $initialUser) {
				$statInsertUserInUserGroup->execute(array(
						"user_group_id"=>$userGroupModel->getId(),
						"user_account_id"=>$initialUser->getId(),
						"added_at"=>\TimeSource::getFormattedForDataBase(),
				));
			}

			if (!$inTransaction) $DB->commit();
		} catch (Exception $e) {
			if (!$inTransaction) $DB->rollBack();
		}

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

