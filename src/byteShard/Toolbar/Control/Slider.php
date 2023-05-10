<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;

/**
 * Class Slider
 * @package byteShard\CellContent\Toolbar
 */
class Slider extends Toolbar\ToolbarObject
{
    use Toolbar\Length;
    use Toolbar\ValueMax;
    use Toolbar\ValueMin;
    use Toolbar\ValueNow;
    use Toolbar\TextMax;
    use Toolbar\TextMin;
    use Toolbar\Tooltip;

    protected string $type = 'slider';

    public function __construct(string $id, int $min = 0, int $max = 0)
    {
        parent::__construct($id);
        $this->tooltipType = 'toolTip';
        $this->valueMin    = $min;
        $this->valueMax    = $max;
    }
}
