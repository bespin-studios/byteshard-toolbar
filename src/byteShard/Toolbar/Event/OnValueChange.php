<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Event;

use byteShard\Internal\Event\ToolbarEvent;

/**
 * Class OnValueChange
 * @package byteShard\Toolbar\Event
 */
class OnValueChange extends ToolbarEvent
{
    protected static string $event = 'onValueChange';
}
