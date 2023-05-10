<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait ImgDisabled
 * @package byteShard\Internal\Toolbar
 * @property string $imgDis
 */
trait ImgDisabled
{
    protected string $imgDis = '';

    /**
     * path to image for the disabled state of the Toolbar Object
     * @param string $string
     * @return $this
     */
    public function setImageDisabled(string $string): self
    {
        $this->imgDis = $string;
        return $this;
    }
}
