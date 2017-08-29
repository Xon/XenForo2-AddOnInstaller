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
}
