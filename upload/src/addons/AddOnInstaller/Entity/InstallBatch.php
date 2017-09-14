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
 * Class InstallBatch
 *
 * @property int addon_install_batch_id
 * @property int install_date
 * @property int addon_count
 * @property bool is_completed
 * @property string deploy_method
 * @property string username
 * @property int user_id
 * @property InstallBatchEntry Entry
 */
class InstallBatch extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_addon_install_batch';
        $structure->shortName = 'AddOnInstaller:InstallBatch';
        $structure->primaryKey = 'addon_install_batch_id';
        $structure->columns = [
            'addon_install_batch_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'install_date'           => ['type' => self::UINT, 'required' => true, 'default' => \XF::$time],
            'addon_count'            => ['type' => self::UINT, 'default' => 0],
            'is_completed'           => ['type' => self::BOOL, 'default' => false],
            'deploy_method'          => ['type' => self::STR, 'required' => true, 'maxLength' => 50],
            'user_id'                => ['type' => self::UINT, 'required' => true],
            'username'               => ['type' => self::STR, 'required' => true, 'maxLength' => 50],
        ];
        $structure->getters = [];
        $structure->relations = [
            'Entry' => [
                'entity'     => 'AddOnInstaller:InstallBatchEntry',
                'type'       => self::TO_MANY,
                'conditions' => 'addon_install_batch_id',
            ],
        ];

        return $structure;
    }
}
