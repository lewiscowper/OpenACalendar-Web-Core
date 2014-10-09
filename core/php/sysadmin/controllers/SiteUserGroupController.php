<?php

namespace sysadmin\controllers;

use repositories\builders\UserAccountRepositoryBuilder;
use repositories\UserPermissionsRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserGroupRepository;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class SiteUserGroupController {


	protected $parameters = array();

	protected function build($siteid, $id, Request $request, Application $app) {
		$this->parameters = array();


		$sr = new SiteRepository();
		$this->parameters['site'] = $sr->loadById($siteid);

		if (!$this->parameters['site']) {
			$app->abort(404);
		}


		$sr = new UserGroupRepository();
		$this->parameters['usergroup'] = $sr->loadByIdInSite($id, $this->parameters['site']);

		if (!$this->parameters['usergroup']) {
			$app->abort(404);
		}

	}

	function index($siteid, $id, Request $request, Application $app) {

		$this->build($siteid, $id, $request, $app);

		$urb = new UserAccountRepositoryBuilder();
		$urb->setInUserGroup($this->parameters['usergroup']);
		$this->parameters['users'] = $urb->fetchAll();

		$r = new UserPermissionsRepository();
		$this->parameters['userpermissions'] = $r->getPermissionsForUserGroup($this->parameters['usergroup']);

		return $app['twig']->render('sysadmin/siteusergroup/index.html.twig', $this->parameters);

	}


}


