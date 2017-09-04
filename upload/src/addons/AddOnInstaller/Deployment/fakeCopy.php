<?php

namespace AddOnInstaller\Deployment;

class fakeCopy extends AbstractDeployment
{
    public function copy($source, $dest)
    {
        \XF::logException(new \Exception("copying file $source to $dest"), false);
        return true;
    }

    protected function _stop()
    {
        throw new \Exception("Aborting deployment");
    }
}