<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;

/**
 * Class Text
 * @package byteShard\CellContent\Toolbar
 */
class Text extends Toolbar\ToolbarObject
{
    use Toolbar\Text;
    use Toolbar\Tooltip;

    protected string $type = 'text';
}
