<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

/**
 * Trait Tooltip
 * @package byteShard\Internal\Toolbar
 * @property string $tooltip
 * @property string $tooltipType
 */
trait Tooltip
{
    protected string $tooltip     = '';
    protected string $tooltipType = 'title';

    /**
     * the tooltip value of the Toolbar Object
     * @param string $tooltip
     * @return $this
     */
    public function setTooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;
        return $this;
    }
}
