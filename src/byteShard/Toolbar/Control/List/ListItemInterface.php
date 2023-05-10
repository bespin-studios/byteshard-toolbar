<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control\List;

interface ListItemInterface
{
    public function getItem(string $itemId, string $nonce): string|array;
}