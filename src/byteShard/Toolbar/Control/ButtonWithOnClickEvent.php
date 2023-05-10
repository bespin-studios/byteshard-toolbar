<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Action;
use byteShard\Toolbar\Event;

/**
 * Class ButtonWithOnClickEvent
 * @package byteShard\CellContent\Toolbar
 */
class ButtonWithOnClickEvent extends Button
{
    /**
     * ButtonWithOnClickEvent constructor.
     * @param string $id
     * @param Action ...$actions
     */
    public function __construct(string $id, Action ...$actions)
    {
        parent::__construct($id);
        $this->addEvents(new Event\OnClick(...$actions));
    }
}
