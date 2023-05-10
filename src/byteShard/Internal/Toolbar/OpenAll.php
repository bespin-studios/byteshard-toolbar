<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait OpenAll
 * @package byteShard\Internal\Toolbar
 * @property bool $openAll
 */
trait OpenAll
{
    protected bool $openAll = false;

    /**
     * if the parameter is set to true you can open the dropdown list by clicking both on the label and arrow of a button. If the parameter is set to false the dropdown list can be opened just by clicking on the arrow. The default value is false
     * @param bool $bool = true [optional]
     * @return $this
     */
    public function setOpenAll(bool $bool = true): self
    {
        $this->openAll = $bool;
        return $this;
    }
}
