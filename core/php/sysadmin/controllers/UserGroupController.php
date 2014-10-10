<?php

namespace sysadmin\controllers;

use repositories\builders\UserAccountRepositoryBuilder;
use repositories\UserAccountRepository;
use repositories\UserPermissionsRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserGroupRepository;

use sysadmin\ActionParser;
use sysadmin\forms\ActionForm;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class UserGroupController {


	protected $parameters = array();

	protected function build($id, Request $request, Application $app) {
		$this->parameters = array();

		$sr = new UserGroupRepository();
		$this->parameters['usergroup'] = $sr->loadById($id);

		if (!$this->parameters['usergroup']) {
			$app->abort(404);
		}

	}

	function index($id, Request $request, Application $app) {

		$this->build($id, $request, $app);


		$form = $app['form.factory']->create(new ActionForm());

		if ('POST' == $request->getMethod()) {
			$form->bind($request);


			if ($form->isValid()) {
				$data = $form->getData();
				$action = new ActionParser($data['action']);

				if ($action->getCommand() == 'addusername') {
					$uar = new UserAccountRepository();
					$user = $uar->loadByUserName($action->getParam(0));
					if ($user) {
						$ugr = new UserGroupRepository();
						$ugr->addUserToGroup($user, $this->parameters['usergroup'], userGetCurrent());
						return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
					}
				} else if ($action->getCommand() == 'removeusername') {
					$uar = new UserAccountRepository();
					$user = $uar->loadByUserName($action->getParam(0));
					if ($user) {
						$ugr = new UserGroupRepository();
						$ugr->removeUserFromGroup($user, $this->parameters['usergroup'], userGetCurrent());
						return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
					}
				}

			}

		}

		$this->parameters['form'] = $form->createView();

		$urb = new UserAccountRepositoryBuilder();
		$urb->setInUserGroup($this->parameters['usergroup']);
		$this->parameters['users'] = $urb->fetchAll();

		$r = new UserPermissionsRepository();
		$this->parameters['userpermissions'] = $r->getPermissionsForUserGroup($this->parameters['usergroup']);

		return $app['twig']->render('sysadmin/usergroup/index.html.twig', $this->parameters);

	}


}


