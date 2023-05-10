<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\ClientData\EncryptedObjectValueInterface;
use byteShard\Toolbar\Control\List\ListColumn;
use byteShard\Toolbar\Control\List\ListItem;
use byteShard\Toolbar\Control\List\ListItemInterface;
use byteShard\Toolbar\Control\List\Separator;

/**
 * Class ButtonWithOnClickEvent
 * @package byteShard\CellContent\Toolbar
 */
class ButtonWithList extends ButtonWithOnClickEvent implements EncryptedObjectValueInterface
{
    private array $list = [];

    /**
     * @API
     * @param array $list
     * @return $this
     */
    public function setList(array $list): self
    {
        foreach ($list as $item) {
            if ($item instanceof ListItemInterface) {
                $this->list[] = $item;
            } else {
                if ($item === 'separator') {
                    $this->list[] = new Separator();
                } elseif (is_array($item) && array_key_exists('id', $item)) {
                    $columns = [];
                    foreach ($item as $column => $text) {
                        if ($column !== 'id') {
                            $columns[] = new ListColumn($column, $text);
                        }
                    }
                    $this->list[] = new ListItem($item['id'], ...$columns);
                }
            }
        }
        return $this;
    }

    public function getList(string $nonce): array
    {
        $result = [];
        $fields = [];
        foreach ($this->list as $item) {
            $result['values'][] = $item->getItem($this->getToolbarObjectName(), $nonce);
            if ($item instanceof ListItem) {
                $fields = array_unique(array_merge($fields, $item->getFields()));
            }
        }
        $result['fields'] = implode(',', $fields);
        return $result;
    }
}
