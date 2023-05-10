<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;

/**
 * Class Input
 * @package byteShard\CellContent\Toolbar
 */
class Input extends Toolbar\ToolbarObject
{
    use Toolbar\Tooltip;
    use Toolbar\Value;
    use Toolbar\Width;
    use Toolbar\Disabled;

    protected string $type = 'buttonInput';
}
