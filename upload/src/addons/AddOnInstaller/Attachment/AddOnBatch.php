<?php

/*
 * This file is part of a XenForo add-on.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AddOnInstaller\Attachment;

use XF\Attachment\AbstractHandler;
use XF\Entity\Attachment;
use XF\Mvc\Entity\Entity;

class AddOnBatch extends AbstractHandler
{

    public function canView(Attachment $attachment, Entity $container, &$error = null)
    {
        return \XF::visitor()->getIsSuperAdmin();
    }

    public function canManageAttachments(array $context, &$error = null)
    {
        return \XF::visitor()->getIsSuperAdmin();
    }

    public function onAttachmentDelete(Attachment $attachment, Entity $container = null)
    {
        // TODO: block deleting these
    }

    public function getConstraints(array $context)
    {
        return [
            'extensions' => ['zip','xml'],
            'size' => \XF::app()->uploadMaxFilesize / 1024,
            'width' => 0,
            'height' => 0,
            'count' => 0
        ];
    }

    public function getContainerIdFromContext(array $context)
    {
        return isset($context['install_batch_id']) ? intval($context['install_batch_id']) : null;
    }

    public function getContainerLink(Entity $container, array $extraParams = [])
    {
        return \XF::app()->router('admin')->buildLink('add-ons/install-batch', $container, $extraParams);
    }

    public function getContext(Entity $entity = null, array $extraContext = [])
    {
        if ($entity instanceof \AddOnInstaller\Entity\InstallBatch)
        {
            $extraContext['install_batch_id'] = $entity->install_batch_id;
        }
        else if (!$entity)
        {
            // need nothing
        }
        else
        {
            throw new \InvalidArgumentException("Entity must be add-on install batch");
        }

        return $extraContext;
    }
}
