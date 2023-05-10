<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

use byteShard\Toolbar\Control\Button;
use byteShard\Toolbar\Control\Separator;

/**
 * Trait Nested
 * @package byteShard\Internal\Toolbar
 * @property array $nestedItems
 */
trait Nested
{
    /** @var ToolbarObject[] */
    public array $nestedItems = [];

    /**
     * @param ToolbarObject ...$toolbar_objects
     * @return $this
     */
    public function addToolbarObject(ToolbarObject ...$toolbar_objects): self
    {
        foreach ($toolbar_objects as $toolbar_object) {
            if (($toolbar_object instanceof Button) || ($toolbar_object instanceof Separator)) {
                $this->nestedItems[] = $toolbar_object;
            }
        }
        return $this;
    }
}
