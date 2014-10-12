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
class UserPermissionsSiteTest extends \PHPUnit_Framework_TestCase {


	function testSiteOwnerAllEdit() {
		global $CONFIG;
		$DB = getNewTestDB();
		addCountriesToTestDB();
		$app = getNewTestApp();

		$userOwner = new UserAccountModel();
		$userOwner->setEmail("test@jarofgreen.co.uk");
		$userOwner->setUsername("test");
		$userOwner->setPassword("password");

		$userVerified = new UserAccountModel();
		$userVerified->setEmail("verified@jarofgreen.co.uk");
		$userVerified->setUsername("verified");
		$userVerified->setPassword("password");

		$userUnverified = new UserAccountModel();
		$userUnverified->setEmail("unverified@jarofgreen.co.uk");
		$userUnverified->setUsername("unverified");
		$userUnverified->setPassword("password");

		$userRepo = new UserAccountRepository();
		$userRepo->create($userOwner);
		$userRepo->verifyEmail($userOwner);
		$userRepo->create($userVerified);
		$userRepo->verifyEmail($userVerified);
		$userRepo->create($userUnverified);

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		$siteModel = new \models\SiteModel();
		$siteModel->setTitle("Test");
		$siteModel->setSlug("test");

		$siteRepository = new \repositories\SiteRepository();
		$countryRepository = new \repositories\CountryRepository();
		$siteRepository->create($siteModel, $userOwner, array($countryRepository->loadByTwoCharCode("GB")), getSiteQuotaUsedForTesting(), true);

		## Check!

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		$permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel);
		$this->assertEquals(2, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel);
		$this->assertEquals(1, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel);
		$this->assertEquals(0, count($permissions->getPermissions()));

	}

	function testSiteOwnerSpecificEdit() {
		global $CONFIG;
		$DB = getNewTestDB();
		addCountriesToTestDB();
		$app = getNewTestApp();

		$userOwner = new UserAccountModel();
		$userOwner->setEmail("test@jarofgreen.co.uk");
		$userOwner->setUsername("test");
		$userOwner->setPassword("password");

		$userVerified = new UserAccountModel();
		$userVerified->setEmail("verified@jarofgreen.co.uk");
		$userVerified->setUsername("verified");
		$userVerified->setPassword("password");

		$userUnverified = new UserAccountModel();
		$userUnverified->setEmail("unverified@jarofgreen.co.uk");
		$userUnverified->setUsername("unverified");
		$userUnverified->setPassword("password");

		$userRepo = new UserAccountRepository();
		$userRepo->create($userOwner);
		$userRepo->verifyEmail($userOwner);
		$userRepo->create($userVerified);
		$userRepo->verifyEmail($userVerified);
		$userRepo->create($userUnverified);

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		$siteModel = new \models\SiteModel();
		$siteModel->setTitle("Test");
		$siteModel->setSlug("test");

		$siteRepository = new \repositories\SiteRepository();
		$countryRepository = new \repositories\CountryRepository();
		$siteRepository->create($siteModel, $userOwner, array($countryRepository->loadByTwoCharCode("GB")), getSiteQuotaUsedForTesting(), false);

		## Check!

		$extensionsManager = new ExtensionManager($app);
		$userPerRepo = new \repositories\UserPermissionsRepository($extensionsManager);

		$permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel);
		$this->assertEquals(2, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel);
		$this->assertEquals(0, count($permissions->getPermissions()));

		$permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel);
		$this->assertEquals(0, count($permissions->getPermissions()));

	}

}
