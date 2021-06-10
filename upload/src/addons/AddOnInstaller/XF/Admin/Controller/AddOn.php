<?php

namespace AddOnInstaller\XF\Admin\Controller;

/**
 * Extends \XF\Admin\Controller\AddOn
 */
class AddOn extends XFCP_AddOn
{
	public function actionCheckForUpdate(\XF\Mvc\ParameterBag $params)
	{
		$addOn = $this->assertAddOnAvailable($params->addon_id_url);

		$addOnRepo = $this->repository('XF:AddOn');
		$addOnRepo->checkForUpdate($addOn);
	}
}