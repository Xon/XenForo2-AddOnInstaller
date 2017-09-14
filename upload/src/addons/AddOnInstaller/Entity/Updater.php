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
 * Class Updater
 *
 * @property string addon_id
 * @property string update_url
 * @property bool check_updates
 * @property int last_checked
 * @property string latest_version
 * @property string skip_version
 * @property \XF\Entity\AddOn AddOn
 */
class Updater extends Entity
{

    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_addon_update_check';
        $structure->shortName = 'AddOnInstaller:Updater';
        $structure->primaryKey = 'addon_id';
        $structure->columns = [
            'addon_id'       => ['type' => self::STR, 'maxLength' => 25, 'required' => true],
            'update_url'     => ['type' => self::STR, 'maxLength' => 250, 'default' => ''],
            'check_updates'  => ['type' => self::BOOL],
            'last_checked'   => ['type' => self::UINT, 'default' => 0],
            'latest_version' => ['type' => self::STR, 'maxLength' => 30, 'default' => ''],
            'skip_version'   => ['type' => self::STR, 'maxLength' => 30, 'default' => ''],
        ];
        $structure->getters = [];
        $structure->relations = [
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
