<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait MaxOpen
 * @package byteShard\Internal\Toolbar
 * @property int $maxOpen
 */
trait MaxOpen
{
    protected ?int $maxOpen = null;

    /** sets the number of items visible at once. The other items can be gotten by scrolling down the dropdown list
     * @param int $maxOpen
     * @return $this
     */
    public function setMaxOpen(int $maxOpen): self
    {
        $this->maxOpen = $maxOpen;
        return $this;
    }
}
