<?php

namespace AddOnInstaller\XF;

use XF\AddOn\DataType;
use XF\AddOn\DataManager;
use XF\Mvc\Entity\Entity;

class DevelopmentOutput extends XFCP_DevelopmentOutput
{
    protected $addonIds = [];

    public function export(Entity $entity)
    {
        $ret = parent::export($entity);

        if (isset($entity->addon_id) && \XF::options()->addonInstaller_auto_export_data && $entity->addon_id !== 'XF')
        {
            $shortName = $entity->structure()->shortName;
            $this->addonIds[$shortName][$entity->addon_id] = true;
            \XF::runOnce('exportData_'.$shortName, function() use ($shortName)
            {
                if (!empty($this->addonIds[$shortName]))
                {
                    $this->_bulkExport($shortName, array_keys($this->addonIds[$shortName]));
                }
            });
        }

        return $ret;
    }

    public function delete(Entity $entity, $new = true)
    {
        $ret = parent::delete($entity, $new);

        if (isset($entity->addon_id) && \XF::options()->addonInstaller_auto_export_data && $entity->addon_id !== 'XF')
        {
            $shortName = $entity->structure()->shortName;
            $this->addonIds[$shortName][$entity->addon_id] = true;
            \XF::runOnce('exportData_'.$shortName, function() use ($shortName)
            {
                if (!empty($this->addonIds[$shortName]))
                {
                    $this->_bulkExport($shortName, array_keys($this->addonIds[$shortName]));
                }
            });
        }

        return $ret;
    }

    public function _bulkExport($shortName, $addOns)
    {
        $dataManager = \XF::app()->addOnDataManager();
        $handler = $dataManager->getDataTypeHandler($shortName);
        foreach($addOns as $addOnId)
        {
            if (empty($addOnId) || $addOnId == 'XF')
            {
                continue;
            }
            $containerName = $handler->getContainerTag();

            $document = new \DOMDocument('1.0', 'utf-8');
            $document->formatOutput = true;
            $container = $document->createElement($containerName);
            $entriesAdded = $handler->exportAddOnData($addOnId, $container);
            if ($entriesAdded)
            {
                $this->_writeDataFile($addOnId, $containerName, $container);
            }
        }
    }

    public function prepareAddOnIdForPath($addOnId)
    {
        if (strpos($addOnId, '/') !== false)
        {
            return str_replace('/', DIRECTORY_SEPARATOR, $addOnId);
        }
        else
        {
            return $addOnId;
        }
    }

    protected function _writeDataFile($addOnId, $containerName, \DOMNode $container)
    {
        $addOn = \XF::em()->find('XF:AddOn', $addOnId);
        if (!$addOn)
        {
            return;
        }

        $ds = DIRECTORY_SEPARATOR;
        $addOnDir = \XF::getAddOnDirectory() . $ds . $this->prepareAddOnIdForPath($addOnId);
        $dataDir = $addOnDir . $ds . '_data';

        \XF\Util\File::createDirectory($dataDir, false);
        if (!is_writable($dataDir))
        {
            throw new \InvalidArgumentException(\XF::phrase('add_on_directory_x_is_not_writable', ['dir' => $dataDir]));
        }

        $newDoc = new \DOMDocument('1.0', 'utf-8');
        $newDoc->formatOutput = true;
        $newDoc->appendChild($newDoc->importNode($container, true));
        $xml = $newDoc->saveXML();

        file_put_contents($dataDir . $ds . "$containerName.xml", $xml);
    }
}
