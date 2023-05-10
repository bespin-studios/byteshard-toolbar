<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Action;

use byteShard\Cell;
use byteShard\ID\IDElement;
use byteShard\Internal\Action;
use byteShard\Internal\Action\ActionResultInterface;

/**
 * Class SetToolbarListID
 * @package byteShard\Action
 */
class SetToolbarListID extends Action implements Action\ControlIdInterface
{
    private array  $cells     = [];
    private string $controlId = '';

    /**
     * SetToolbarListID constructor.
     * @param string ...$cells
     */
    public function __construct(string ...$cells)
    {
        parent::__construct();
        foreach ($cells as $cell) {
            $cell_name               = Cell::getContentCellName($cell);
            $this->cells[$cell_name] = $cell_name;
        }
        $this->addUniqueID($this->cells);
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setControlID(string $id): self
    {
        $this->controlId = $id;
        return $this;
    }

    protected function runAction(): ActionResultInterface
    {
        $container = $this->getLegacyContainer();
        if ($container instanceof Cell) {
            $clientData = $this->getClientData();
            $listId     = $clientData?->{$this->controlId} ?? null;
            $list       = json_decode($listId, true);
            if (!is_array($list)) {
                return new Action\ActionResult(true);
            }
            if (empty($this->cells)) {
                $container->addSelectedIDElements(new IDElement($this->controlId, $list['id']));
                //$container->setToolbarListID($this->control_id, $id['listId']);
            } else {
                $cells = $this->getCells($this->cells);
                foreach ($cells as $cell) {
                    //$cell->setToolbarListID($this->control_id, $id['listid']);
                    $cell->addSelectedIDElements(new IDElement($this->controlId, $list['id']));
                }
            }
        }
        return new Action\ActionResult();
    }
}
