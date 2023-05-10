<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

use byteShard\Toolbar\Control\TwoStateButton\State;

/**
 * Trait Selected
 * @package byteShard\Internal\Toolbar
 * @property bool $selected
 */
trait Selected
{
    protected bool $selected = false;

    /**
     * the Selected State of a Two State Button Toolbar Object. The default value is false
     */
    public function setSelected(State|bool $bool = true): self
    {
        if ($bool instanceof State) {
            $this->selected = $bool->getState();
        } else {
            $this->selected = $bool;
        }
        return $this;
    }
}
