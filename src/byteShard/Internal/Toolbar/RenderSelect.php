<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait RenderSelect
 * @package byteShard\Internal\Toolbar
 */
trait RenderSelect
{
    protected bool $renderSelect = false;

    /**
     * if the parameter is set to true a button 'remembers' the selected item and keeps it selected on the next opening. The default value is false
     * @param bool $bool = true [optional]
     */
    public function setRenderSelect(bool $bool = true): self
    {
        $this->renderSelect = $bool;
        return $this;
    }
}
