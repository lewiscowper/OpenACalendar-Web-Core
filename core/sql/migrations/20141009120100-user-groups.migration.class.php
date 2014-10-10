<?php

namespace db\migrations;
use repositories\builders\SiteRepositoryBuilder;
use repositories\builders\UserAccountRepositoryBuilder;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */

class Migration20141009120100 extends Migration {

	public function __construct() {
		$this->id = "20141009120100-user-groups";
	}


	public  function performMigration(\PDO $db, \TimeSource $timeSource, \Config $config) {
		$statInsertUserGroupInfo = $db->prepare("INSERT INTO user_group_information (title,is_in_index,is_includes_verified_users,created_at) ".
			"VALUES (:title,:is_in_index,:is_includes_verified_users,:created_at) RETURNING id");
		$statInsertUserGroupHistory = $db->prepare("INSERT INTO user_group_history (user_group_id,title,is_in_index,is_includes_verified_users,created_at) ".
			"VALUES (:user_group_id,:title,:is_in_index,:is_includes_verified_users,:created_at)");

		$statInsertUserGroupInSite = $db->prepare("INSERT INTO user_group_in_site (user_group_id,site_id,added_at) ".
			"VALUES (:user_group_id,:site_id,:added_at)");
		$statInsertUserInUserGroup = $db->prepare("INSERT INTO user_in_user_group (user_group_id, user_account_id, added_at) ".
			"VALUES (:user_group_id, :user_account_id, :added_at)");
		$statInsertPermissionInUserGroup = $db->prepare("INSERT INTO permission_in_user_group (user_group_id,extension_id, permission_key,added_at) ".
			"VALUES (:user_group_id,:extension_id, :permission_key,:added_at)");

		// INDEX

		$statInsertUserGroupInfo->execute(array(
			"title"=>"Can Create Sites",
			"is_in_index"=>"1",
			"is_includes_verified_users"=> $config->canCreateSitesVerifiedEditorUsers ? "1" : "0",
			"created_at"=>$timeSource->getFormattedForDataBase(),
		));
		$data = $statInsertUserGroupInfo->fetch();
		$id = $data['id'];
		$statInsertUserGroupHistory->execute(array(
			"user_group_id"=>$id,
			"title"=>"Can Create Sites",
			"is_in_index"=>"1",
			"is_includes_verified_users"=> $config->canCreateSitesVerifiedEditorUsers ? "1" : "0",
			"created_at"=>$timeSource->getFormattedForDataBase(),
		));

		$statInsertPermissionInUserGroup->execute(array(
			"user_group_id"=>$id,
			"extension_id"=>"org.openacalendar",
			"permission_key"=>"CREATE_SITE",
			"added_at"=>$timeSource->getFormattedForDataBase(),
		));

		// SITE
		$srb = new SiteRepositoryBuilder();
		foreach($srb->fetchAll() as $site) {

			// edit

			$statInsertUserGroupInfo->execute(array(
				"title"=>"Editors",
				"is_in_index"=>"0",
				"is_includes_verified_users"=> $site->getIsAllUsersEditors() ? "1" : "0",
				"created_at"=>$timeSource->getFormattedForDataBase(),
			));
			$data = $statInsertUserGroupInfo->fetch();
			$editId = $data['id'];
			$statInsertUserGroupHistory->execute(array(
				"user_group_id"=>$editId,
				"title"=>"Editors",
				"is_in_index"=>"0",
				"is_includes_verified_users"=> $site->getIsAllUsersEditors() ? "1" : "0",
				"created_at"=>$timeSource->getFormattedForDataBase(),
			));

			$statInsertUserGroupInSite->execute(array(
				"user_group_id"=>$editId,
				"site_id"=>$site->getId(),
				"added_at"=>$timeSource->getFormattedForDataBase(),
			));

			$statInsertPermissionInUserGroup->execute(array(
				"user_group_id"=>$editId,
				"extension_id"=>"org.openacalendar",
				"permission_key"=>"CALENDAR_EDIT",
				"added_at"=>$timeSource->getFormattedForDataBase(),
			));

			// admin

			$statInsertUserGroupInfo->execute(array(
				"title"=>"Admins",
				"is_in_index"=>"0",
				"is_includes_verified_users"=> "0",
				"created_at"=>$timeSource->getFormattedForDataBase(),
			));
			$data = $statInsertUserGroupInfo->fetch();
			$adminId = $data['id'];
			$statInsertUserGroupHistory->execute(array(
				"user_group_id"=>$adminId,
				"title"=>"Admins",
				"is_in_index"=>"0",
				"is_includes_verified_users"=> "0",
				"created_at"=>$timeSource->getFormattedForDataBase(),
			));

			$statInsertUserGroupInSite->execute(array(
				"user_group_id"=>$adminId,
				"site_id"=>$site->getId(),
				"added_at"=>$timeSource->getFormattedForDataBase(),
			));

			$statInsertPermissionInUserGroup->execute(array(
				"user_group_id"=>$adminId,
				"extension_id"=>"org.openacalendar",
				"permission_key"=>"CALENDAR_ADMINISTRATE",
				"added_at"=>$timeSource->getFormattedForDataBase(),
			));

			// Users
			$uarb = new UserAccountRepositoryBuilder();
			$uarb->setCanEditSite($site);
			foreach($uarb->fetchAll() as $user) {
				if ($user->getIsSiteEditor() || $user->getIsSiteAdministrator() || $user->getIsSiteOwner()) {
					$statInsertUserInUserGroup->execute(array(
						"user_group_id"=>$editId,
						"user_account_id"=>$user->getId(),
						"added_at"=>$timeSource->getFormattedForDataBase(),
					));
				}
				if ($user->getIsSiteAdministrator() || $user->getIsSiteOwner()) {
					$statInsertUserInUserGroup->execute(array(
						"user_group_id"=>$adminId,
						"user_account_id"=>$user->getId(),
						"added_at"=>$timeSource->getFormattedForDataBase(),
					));
				}
			}

		}




	}



}