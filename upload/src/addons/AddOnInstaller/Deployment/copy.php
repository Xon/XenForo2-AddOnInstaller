<?php

namespace AddOnInstaller\Deployment;

class copy extends AbstractDeployment
{
    public function copy($source, $dest)
    {
        return \XF::app()->fs()->copy($source, $dest);
    }

    protected function _stop()
    {
        \XF::app()->fs()->flushCache();
        $this->installerRepository()->InvalidateOpCache();
    }
}