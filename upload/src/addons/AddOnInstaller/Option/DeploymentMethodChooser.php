<?php

namespace AddOnInstaller\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;

class DeploymentMethodChooser extends AbstractOption
{
    public static function renderOption(Option $option, array $htmlParams)
    {
        /** @var string $$option_value */
        $option_value = $option->getOptionValue();
        /** @var \AddOnInstaller\Repository\Installer $installerRepo */
        $installerRepo = \XF::app()->repository('AddOnInstaller:Installer');
        $methods = $installerRepo->getAddonDeploymentMethods();

        $choices = array();
        foreach($methods as $key => $method)
        {
            $choices[$key] = \XF::phrase('deployment_method_'.$key);
        }

        return self::getTemplate('option_deployment_select', $option, $htmlParams, [
            'formatParams' => $choices,
        ]);
    }
}