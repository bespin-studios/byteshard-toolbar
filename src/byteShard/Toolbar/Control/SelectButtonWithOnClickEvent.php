<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Action;
use byteShard\Toolbar\Event;

/**
 * Class SelectButtonWithOnClickEvent
 * @package byteShard\CellContent\Toolbar
 */
class SelectButtonWithOnClickEvent extends SelectButton
{
    public function __construct(string $id, Action ...$actions)
    {
        parent::__construct($id);
        $this->addEvents(new Event\OnClick(...$actions));
    }
}
