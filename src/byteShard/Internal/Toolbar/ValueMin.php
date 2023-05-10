<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait ValueMin
 * @package byteShard\Internal\Toolbar
 * @property int $valueMin
 */
trait ValueMin
{
    protected int $valueMin = 0;

    /**
     * minimum possible value;
     * @param int $min
     * @return $this
     */
    public function setValueMin(int $min): self
    {
        $this->valueMin = $min;
        return $this;
    }
}
