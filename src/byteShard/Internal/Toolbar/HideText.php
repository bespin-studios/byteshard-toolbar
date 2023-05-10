<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait HideText
 * @package byteShard\Internal\Toolbar
 * @property bool $hideText
 */
trait HideText
{
    protected bool $hideText = false;

    /**
     * To hide the Label of the Toolbar Object
     * to create a toolbar button with just an icon
     * @param bool $hideText
     * @return $this
     */
    public function setHideText(bool $hideText = true): self
    {
        $this->hideText = $hideText;
        return $this;
    }
}
