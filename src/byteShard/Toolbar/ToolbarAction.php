<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar;

use byteShard\Event\EventActionInterface;
use byteShard\Internal\Action;

class ToolbarAction implements EventActionInterface
{
    private array $actions;
    private string $objectId;
    public function __construct(string $objectId, Action ...$actions) {
        $this->objectId = $objectId;
        $this->actions = $actions;
    }

    public function getActions(?string $objectId): array
    {
        if ($this->objectId === $objectId) {
            return $this->actions;
        }
        return [];
    }
}