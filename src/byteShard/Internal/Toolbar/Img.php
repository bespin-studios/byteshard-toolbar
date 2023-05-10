<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Img
 * @package byteShard\Internal\Toolbar
 * @property string $img
 */
trait Img
{
    protected string $img = '';

    /**
     * path to image for the enabled state of the Toolbar Object
     * @param string $string
     * @return $this
     */
    public function setImage(string $string): self
    {
        $this->img = $string;
        return $this;
    }
}
