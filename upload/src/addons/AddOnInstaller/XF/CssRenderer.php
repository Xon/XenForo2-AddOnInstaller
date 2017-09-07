<?php

namespace AddOnInstaller\XF;

use XF\App;
use XF\Template\Templater;

class CssRenderer extends XFCP_CssRenderer
{
    public function __construct(App $app, Templater $templater, \Doctrine\Common\Cache\CacheProvider $cache = null)
    {
        parent::__construct($app, $templater, $cache);

        $config = $app->config();
        if ($config['development']['enabled'])
        {
            $this->allowCached = true;
        }
    }
}
