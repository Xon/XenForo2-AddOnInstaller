<?php

/*
 * This file is part of a XenForo add-on.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AddOnInstaller;

/**
 * Contains event listener callbacks.
 */
class Listener
{
    public static function appSetup(\XF\Admin\App $app)
    {
        $loader = \XF::$autoLoader;
        $loader->addClassMap(array('Git' => 'src/addons/AddOnInstaller/vender/Git.php'));
    }

    public static function addon_deployment(&$deployMethods)
    {
        $builtIns = explode(',', \XF::options()->builtin_deploymentmethods);
        foreach($builtIns as $deployMethod)
        {
            $deployMethod = trim($deployMethod);
            if ($deployMethod == 'ftp' && !extension_loaded('ftp'))
            {
                continue;
            }
            if ($deployMethod)
            {
                $deployMethods[$deployMethod] = 'AddOnInstaller/Deployment/' . $deployMethod;
            }
        }
    }
}
