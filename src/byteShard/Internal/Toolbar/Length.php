<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Length
 * @package byteShard\Internal\Toolbar
 * @property int $length
 */
trait Length
{
    protected ?int $length = null;

    /**
     * slider's length (or width/height, it is set in pixels);
     * @param int $length
     * @return $this
     */
    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }
}
