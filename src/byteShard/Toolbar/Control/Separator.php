<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;

/**
 * Class Separator
 * @package byteShard\CellContent\Toolbar
 */
class Separator extends Toolbar\ToolbarObject
{
    protected string $type = 'separator';

    public function __construct()
    {
        parent::__construct('');
    }
}
