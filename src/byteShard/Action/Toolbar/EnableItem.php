<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Action\Toolbar;

use byteShard\Internal\Action\ModifyToolbarControl;

class EnableItem extends ModifyToolbarControl
{
    public function __construct(string $cell, string ...$controls) {
        parent::__construct($cell, 'enableItem', ...$controls);
    }
}
