<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Action;
use byteShard\Toolbar\Event;

/**
 * Class InputWithOnEnterEvent
 * @package byteShard\CellContent\Toolbar
 */
class InputWithOnEnterEvent extends Input
{
    public function __construct(string $id, Action ...$actions)
    {
        parent::__construct($id);
        $this->addEvents(new Event\OnEnter(...$actions));
    }
}
