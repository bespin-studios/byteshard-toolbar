<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Width
 * @package byteShard\Internal\Toolbar
 * @property int $width
 */
trait Width
{
    protected ?int $width = null;

    /**
     * the width of the Toolbar Object
     * @param int $width
     * @return $this
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }
}
