<?php

namespace AddOnInstaller\Deployment;

class ftp extends AbstractDeployment
{
    protected $ftp = null;
/*
    public function is_dir($destination)
    {
        return $this->ftp->isDir($destination);
    }

    public function mkdir($destination)
    {
        return $this->ftp->mkdir($destination, true);
    }
*/
    public function copy($source, $dest)
    {
        throw new \Exception("Not implemented");
        //$this->ftp->put($dest, $source, FTP_BINARY);
        //return @copy($source, $dest);
    }

    protected function _start()
    {
        throw new \Exception("Not implemented");
        /*
        $config = XenForo_Application::getOptions()->deploymentmethod_ftp;
        $host = empty($config['host']) ? '127.0.0.1' : $config['host'];
        $port = empty($config['port']) || intval($config['port']) == 0 ? 21 : $config['port'];
        $username = empty($config['username']) ? 'anonymous' : $config['username'];
        $password = empty($config['password']) ? '' : $config['password'];
        $timeout = empty($config['timeout']) || intval($config['port']) <= 0 ? 5 : $config['timeout'];
        $passive_mode = !empty($config['passive_mode']);
        
        $class = XenForo_Application::resolveDynamicClass('AddOnInstaller_FtpClient_FtpClient');
        $this->ftp = new $class();
        $this->ftp->connect($host, false, $port, $timeout);
        $this->ftp->login($username, $password);
        
        if ($passive_mode)
        {
            $this->ftp->pasv($passive_mode);
        }

        return true;
        */
    }

    protected function _stop()
    {
        $this->ftp = null;
        \XF\Util\Php::resetOpcache();
    }
}