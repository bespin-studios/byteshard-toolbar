<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait ValueMax
 * @package byteShard\Internal\Toolbar
 * @property int $valueMax
 */
trait ValueMax
{
    protected int $valueMax = 0;

    /**
     * maximum possible value;
     * @param int $max
     * @return $this
     */
    public function setValueMax(int $max): self
    {
        $this->valueMax = $max;
        return $this;
    }
}
