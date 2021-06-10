<?php

namespace AddOnInstaller\Repository;


use XF\Mvc\Entity\Repository;

class Installer extends Repository
{
    public function getAddonDeploymentMethods()
    {
        $deployMethods = [];
        \XF::fire('addon_deployment', array(&$deployMethods));
        if (empty($deployMethods))
        {
            $deployMethod = 'copy';
            $deployMethods[$deployMethod] = 'AddOnInstaller/Deployment/' . $deployMethod;
        }
        return $deployMethods;
    }

    public function getAddonDeploymentMethodPhrases()
    {
        $methods = $this->getAddonDeploymentMethods();
        foreach ($methods as $key => &$method)
        {
            $method = \XF::Phrase('deployment_method_' . $key);
        }
        return $methods;
    }
}