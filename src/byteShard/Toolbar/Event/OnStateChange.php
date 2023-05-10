<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Event;

use byteShard\Internal\Event\ToolbarEvent;

/**
 * Class OnStateChange
 * @package byteShard\Toolbar\Event
 */
class OnStateChange extends ToolbarEvent
{
    protected static string $event = 'onStateChange';
}
