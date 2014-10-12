<?php


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */

class UserPermissionsListTest  extends \PHPUnit_Framework_TestCase {

	function testAnonymousCantHaveEditPermission() {

		$app = getNewTestApp();

		$extensionCore = new \ExtensionCore($app);

		$permission = $extensionCore->getUserPermission("CALENDAR_EDIT");

		$userPermissionList = new UserPermissionsList(array($permission), null, false);

		$this->assertFalse( $userPermissionList->hasPermission("org.openacalendar","CALENDAR_EDIT") );
	}

	function testCanHaveEditPermission() {

		$app = getNewTestApp();

		$extensionCore = new \ExtensionCore($app);

		$permission = $extensionCore->getUserPermission("CALENDAR_EDIT");

		$user = new \models\UserAccountModel();
		$user->setIsEditor(true);

		$userPermissionList = new UserPermissionsList(array($permission), $user, false);

		$this->assertTrue( $userPermissionList->hasPermission("org.openacalendar","CALENDAR_EDIT") );
	}

	function testCantHaveEditPermissionWhenUserNotEditor() {

		$app = getNewTestApp();

		$extensionCore = new \ExtensionCore($app);

		$permission = $extensionCore->getUserPermission("CALENDAR_EDIT");

		$user = new \models\UserAccountModel();
		$user->setIsEditor(false);

		$userPermissionList = new UserPermissionsList(array($permission), $user, false);

		$this->assertFalse( $userPermissionList->hasPermission("org.openacalendar","CALENDAR_EDIT") );
	}

	function testCantHaveEditPermissionWhenReadOnly() {

		$app = getNewTestApp();

		$extensionCore = new \ExtensionCore($app);

		$permission = $extensionCore->getUserPermission("CALENDAR_EDIT");

		$user = new \models\UserAccountModel();
		$user->setIsEditor(true);

		$userPermissionList = new UserPermissionsList(array($permission), $user, true);

		$this->assertFalse( $userPermissionList->hasPermission("org.openacalendar","CALENDAR_EDIT") );
	}

}

