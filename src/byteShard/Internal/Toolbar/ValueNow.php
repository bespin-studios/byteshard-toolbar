<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait ValueNow
 * @package byteShard\Internal\Toolbar
 * @property int $valueNow
 */
trait ValueNow
{
    protected int $valueNow = 0;

    /**
     * the value the slider is set to for the moment it is created;
     * @param int $value
     * @return $this
     */
    public function setValueNow(int $value): self
    {
        $this->valueNow = $value;
        return $this;
    }
}
