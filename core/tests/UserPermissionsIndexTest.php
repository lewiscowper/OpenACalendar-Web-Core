<?php

use models\UserAccountModel;
use repositories\UserAccountRepository;
use repositories\UserAccountResetRepository;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class UserPermissionsIndexTest extends \PHPUnit_Framework_TestCase {

	function testAllUsersCreateSiteByDefault() {
		global $CONFIG;
		$CONFIG->canCreateSitesVerifiedEditorUsers = true;
		$DB = getNewTestDB();
		$app = getNewTestApp();

		$user = new UserAccountModel();
		$user->setEmail("test@jarofgreen.co.uk");
		$user->setUsername("test");
		$user->setPassword("password");

		$userRepo = new UserAccountRepository();
		$userRepo->create($user);
		$userRepo->verifyEmail($user);

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		## user can create sites, anon can't!

		$permissions = $userPerRepo->getPermissionsForUserInIndex(null);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInIndex($user);
		$this->assertEquals(1, count($permissions->getPermissions()));



	}


	function testAllUsersCreateSite() {
		global $CONFIG;
		$CONFIG->canCreateSitesVerifiedEditorUsers = false;
		$DB = getNewTestDB();
		$app = getNewTestApp();

		$user = new UserAccountModel();
		$user->setEmail("test@jarofgreen.co.uk");
		$user->setUsername("test");
		$user->setPassword("password");

		$userRepo = new UserAccountRepository();
		$userRepo->create($user);
		$userRepo->verifyEmail($user);

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		## Noone can create sites

		$permissions = $userPerRepo->getPermissionsForUserInIndex(null);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInIndex($user);
		$this->assertEquals(0, count($permissions->getPermissions()));

		## Now create user group for all users

		$userGroupModel = new \models\UserGroupModel();
		$userGroupModel->setTitle("TITLE");
		$userGroupModel->setIsIncludesVerifiedUsers(true);

		$userGroupRepo = new \repositories\UserGroupRepository();
		$userGroupRepo->createForIndex($userGroupModel);

		$userGroupRepo->addPermissionToGroup(new \userpermissions\CreateSiteUserPermission(), $userGroupModel, null);

		## Now user can create sites, anon can't!

		$permissions = $userPerRepo->getPermissionsForUserInIndex(null);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInIndex($user);
		$this->assertEquals(1, count($permissions->getPermissions()));

	}

	function testSpecificUsersCreateSite() {
		global $CONFIG;
		$CONFIG->canCreateSitesVerifiedEditorUsers = false;
		$DB = getNewTestDB();
		$app = getNewTestApp();

		$user = new UserAccountModel();
		$user->setEmail("test@jarofgreen.co.uk");
		$user->setUsername("test");
		$user->setPassword("password");

		$userRepo = new UserAccountRepository();
		$userRepo->create($user);
		$userRepo->verifyEmail($user);

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		## Noone can create sites

		$permissions = $userPerRepo->getPermissionsForUserInIndex(null);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInIndex($user);
		$this->assertEquals(0, count($permissions->getPermissions()));

		## Now create user group for all users

		$userGroupModel = new \models\UserGroupModel();
		$userGroupModel->setTitle("TITLE");

		$userGroupRepo = new \repositories\UserGroupRepository();
		$userGroupRepo->createForIndex($userGroupModel);
		$userGroupRepo->addUserToGroup($user, $userGroupModel);

		$userGroupRepo->addPermissionToGroup(new \userpermissions\CreateSiteUserPermission(), $userGroupModel, null);

		## Now user can create sites, anon can't!

		$permissions = $userPerRepo->getPermissionsForUserInIndex(null);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInIndex($user);
		$this->assertEquals(1, count($permissions->getPermissions()));

	}

}
