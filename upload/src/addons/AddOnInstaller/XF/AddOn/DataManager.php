<?php


namespace AddOnInstaller\XF\AddOn;


use XF\AddOn\AddOn;

class DataManager extends XFCP_DataManager
{
    public function enqueueImportAddOnData(AddOn $addOn)
    {
        if ($addOn->addon_id == 'XF' || !$addOn->isDevOutputAvailable() || !\XF::options()->addonInstaller_auto_import_output)
        {
            return parent::enqueueImportAddOnData($addOn);
        }

        return \XF::app()->jobManager()->enqueueUnique($this->getImportDataJobId($addOn), 'AddOnInstaller:AddOnData', [
            'addon_id' => $addOn->addon_id
        ]);
    }
}
