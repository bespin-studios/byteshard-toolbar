<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Event;

use byteShard\Internal\Event\ToolbarEvent;

/**
 * Class OnClick
 * @package byteShard\Toolbar\Event
 */
class OnClick extends ToolbarEvent
{
    protected static string $event = 'onClick';
}
