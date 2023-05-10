<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Disabled
 * @package byteShard\Internal\Toolbar
 * @property bool $disabled
 */
trait Disabled
{
    protected bool $disabled = false;

    /**
     * the disabled State of a Toolbar Object. The default value is false
     * @param bool $bool = true [optional]
     * @return $this
     */
    public function setDisabled(bool $bool = true): self
    {
        $this->disabled = $bool;
        return $this;
    }
}
