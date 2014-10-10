<?php

namespace site\controllers;

use repositories\UserGroupRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AdminUserGroupController {


	protected $parameters = array();

	protected function build($id, Request $request, Application $app) {
		$this->parameters = array('currentUserWatchesGroup'=>false);



		$ugr = new UserGroupRepository();
		$this->parameters['usergroup'] = $ugr->loadByIdInSite($id, $app['currentSite']);
		if (!$this->parameters['usergroup']) {
			return false;
		}


		return true;
	}

	function show($id, Request $request, Application $app) {

		if (!$this->build($id, $request, $app)) {
			$app->abort(404, "User Group does not exist.");
		}




		return $app['twig']->render('site/adminusergroup/show.html.twig', $this->parameters);
	}




}



