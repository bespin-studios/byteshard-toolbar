<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait TextMax
 * @package byteShard\Internal\Toolbar
 * @property string $textMax
 */
trait TextMax
{
    protected string $textMax = '';

    /**
     * the text displayed to the right of the slider (in case of horizontal slider display) or above the slider (in case of vertical slider display);
     * @param string $text
     * @return $this
     */
    public function setTextMax(string $text): self
    {
        $this->textMax = $text;
        return $this;
    }
}
