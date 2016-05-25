<?php 

/**
 * @file plugins/generic/unregisteredUsers/UnregisteredUsersHandler.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING. 
 *
 * @class UnregisteredUsersHandler
 *
 *
 */

import('classes.handler.Handler');

class UnregisteredUsersHandler extends Handler {	

	function UnregisteredUsersHandler() {
		parent::Handler();
	}

	function index($args, $request) {
	
		$authorizedUserGroups = array(ROLE_ID_SITE_ADMIN,ROLE_ID_MANAGER);
		$userRoles = $this->getAuthorizedContextObject(ASSOC_TYPE_USER_ROLES);

		// redirect to index page if user does not have the rights
		$user = $request->getUser();
		if (!array_intersect($authorizedUserGroups, $userRoles)) {
			$request->redirect(null, 'index');
		}

		$press = $request->getPress();
		$dispatcher = $request->getDispatcher();
		$unregisteredUsersPlugin = PluginRegistry::getPlugin('generic', UNREGISTEREDUSERS_PLUGIN_NAME);

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pageTitle', 'plugins.generic.title.unregisteredUsers');
		$templateMgr->assign('userRoles', $userRoles); // necessary for the backend sidenavi to appear
		$templateMgr->assign('url',	$dispatcher->url($request, ROUTE_PAGE, null,'management',
					'importexport', array('plugin', 'UnregisteredUsersIEImportExportPlugin')));

		$templateMgr->display($unregisteredUsersPlugin->getTemplatePath().'unregisteredUsers.tpl');
	}

}
?>
