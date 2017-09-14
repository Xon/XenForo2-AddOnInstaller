<?php

/*
 * This file is part of a XenForo add-on.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AddOnInstaller\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * Class InstallBatchEntry
 *
 * @property int addon_install_batch_entry_id
 * @property int addon_install_batch_id
 * @property string addon_id
 * @property string version_string
 * @property string install_phase
 * @property bool in_error
 * @property string original_filename
 * @property string source_file
 * @property string extracted_files
 * @property string xml_file
 * @property string resource_url
 * @property int $install_order
 * @property InstallBatch Batch
 * @property \XF\Entity\AddOn AddOn
 */
class InstallBatchEntry extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_addon_install_batch_entry';
        $structure->shortName = 'AddOnInstaller:InstallBatchEntry';
        $structure->primaryKey = 'addon_install_batch_entry_id';
        $structure->columns = [
            'addon_install_batch_entry_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'addon_install_batch_id'       => ['type' => self::UINT, 'required' => true],
            'addon_id'                     => ['type' => self::STR, 'maxLength' => 25, 'default' => ''],
            'version_string'               => ['type' => self::STR, 'maxLength' => 30, 'default' => ''],
            'install_phase'                => [
                'type'          => self::STR, 'default' => 'uploaded',
                'allowedValues' => ['uploaded', 'extracted', 'deployed', 'installed']
            ],
            'in_error'                     => ['type' => self::UINT, 'default' => 0],
            'original_filename'            => ['type' => self::STR, 'default' => '', 'maxLength' => 1024],
            'source_file'                  => ['type' => self::STR, 'default' => '', 'maxLength' => 1024],
            'extracted_files'              => ['type' => self::STR, 'default' => '', 'maxLength' => 1024],
            'xml_file'                     => ['type' => self::STR, 'default' => '', 'maxLength' => 1024],
            'resource_url'                 => ['type' => self::STR, 'default' => '', 'maxLength' => 250],
            'install_order'                => ['type' => self::UINT, 'default' => 0],
        ];
        $structure->getters = [];
        $structure->relations = [
            'Batch' => [
                'entity'     => 'AddOnInstaller:InstallBatch',
                'type'       => self::TO_ONE,
                'conditions' => 'addon_install_batch_id',
                'primary'    => true
            ],
            'AddOn' => [
                'entity'     => 'XF:AddOn',
                'type'       => self::TO_ONE,
                'conditions' => 'addon_id',
                'primary'    => true
            ],
        ];

        return $structure;
    }
}
