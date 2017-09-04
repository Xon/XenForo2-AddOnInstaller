<?php

namespace AddOnInstaller\Deployment;

abstract class AbstractDeployment
{
    /** @var bool */
    protected $isStarted = false;

    public function isStarted()
    {
        return $this->isStarted;
    }

    /**
     * @return bool
     */
    public final function start()
    {
        if ($this->isStarted)
        {
            return true;
        }
        $this->isStarted = true;
        $ret = $this->_start();
        return $ret;
    }

    /**
     * @return bool
     */
    public final function stop()
    {
        $this->isStarted = false;
        return $this->_stop();
    }

    /**
     * @return bool
     */
    protected function _start()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function _stop()
    {
        return true;
    }

    /**
     * @param string $source
     * @param string $dest
     * @return bool
     */
    public abstract function copy($source, $dest);

    /**
     * @return \AddOnInstaller\Repository\Installer|\XF\Mvc\Entity\Repository
     */
    protected function installerRepository()
    {
        return \XF::app()->repository('Installer:Installer');
    }
}