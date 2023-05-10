<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

trait Hidden
{
    protected bool $hidden = false;

    /**
     * the visibility State of a Toolbar Object. The default value is false
     * @param bool $bool = true [optional]
     * @return $this
     */
    public function setHidden(bool $bool = true): self
    {
        $this->hidden = $bool;
        return $this;
    }
}