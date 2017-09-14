<?php


namespace AddOnInstaller\XF;


use League\Flysystem\AdapterInterface;
use League\Flysystem\EventableFilesystem\EventableFilesystem;

class FsMounts extends XFCP_FsMounts
{
    public static function loadDefaultMounts(array $config)
    {
        $mountManager = parent::loadDefaultMounts($config);

        $adapterOverrides = $config['fsAdapters'];

        if (isset($adapterOverrides['addOn-files']))
        {
            $dataAdapter = call_user_func($adapterOverrides['addOn-files']);
        }
        else
        {
            $dataAdapter = static::getLocalAdapter(empty($config['addOnDataPath']) ? 'install/addons/' : $config['addOnDataPath']);
        }

        $addonFiles = new EventableFilesystem($dataAdapter, [
            'visibility' => AdapterInterface::VISIBILITY_PUBLIC
        ]);
        static::addDefaultWriteListeners('addOn-files', $addonFiles);

        $mountManager->mountFilesystem( 'addOn-files', $addonFiles);

        return $mountManager;
    }
}
