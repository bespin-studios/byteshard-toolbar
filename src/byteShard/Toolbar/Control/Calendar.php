<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;
use byteShard\Internal\Toolbar\ToolbarObject;

class Calendar extends ToolbarObject
{
    use Toolbar\Tooltip;
    use Toolbar\Value;
    use Toolbar\Width;

    protected string $type = 'buttonInput';
    private bool $showTime = true;

    public function hideTime(): self
    {
        $this->showTime = false;
        return $this;
    }

    public function showTime(): bool
    {
        return $this->showTime;
    }
}
