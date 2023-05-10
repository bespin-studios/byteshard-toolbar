<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Text
 * @package byteShard\Internal\Toolbar
 * @property string $text
 */
trait Text
{
    protected string $text = '';

    /**
     * the Label of the Toolbar Object
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
