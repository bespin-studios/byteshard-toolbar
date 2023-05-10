<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control\List;

use byteShard\Session;

class ListItem implements ListItemInterface
{
    private readonly array $columns;

    public function __construct(private readonly int|string $id, ListColumn ...$columns)
    {
        $this->columns = $columns;
    }

    public function getFields(): array
    {
        $result = [];
        foreach ($this->columns as $column) {
            $result[] = $column->getId();
        }
        return $result;
    }

    public function getItem(string $itemId, string $nonce): string|array
    {
        $result       = [];
        $result['id'] = Session::encrypt(json_encode(['id' => $this->id]), $nonce);
        foreach ($this->columns as $column) {
            $result[$column->getId()] = $column->getText();
        }
        return $result;
    }
}