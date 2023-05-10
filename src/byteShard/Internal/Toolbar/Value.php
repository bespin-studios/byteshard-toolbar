<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Value
 * @package byteShard\Internal\Toolbar
 * @property string $value
 */
trait Value
{
    protected string $value = '';

    /**
     * the initial value of the Toolbar Object
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
}
