<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait TextMin
 * @package byteShard\Internal\Toolbar
 * @property string $textMin
 */
trait TextMin
{
    protected string $textMin = '';

    /**
     * the text displayed to the left of the slider (in case of horizontal slider display) or below the slider (in case of vertical slider display);
     * @param string $text
     * @return $this
     */
    public function setTextMin(string $text): self
    {
        $this->textMin = $text;
        return $this;
    }
}
