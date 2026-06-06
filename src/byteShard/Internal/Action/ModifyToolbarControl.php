<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Action;

use byteShard\Cell;
use byteShard\Internal\Action;
use byteShard\Session;

abstract class ModifyToolbarControl extends Action
{
    protected string $cell;
    protected array  $controls          = [];
    protected string $modification;
    protected string $modificationValue = '';

    public function __construct(string $cell, string $modification, string ...$controls)
    {
        $this->cell         = Cell::getContentCellName($cell);
        $this->modification = $modification;
        foreach ($controls as $control) {
            if ($control !== '') {
                $this->controls[$control] = $control;
            }
        }
    }

    protected function runAction(): ActionResultInterface
    {
        $cells = $this->getCells([$this->cell]);
        foreach ($cells as $cell) {
            $containerClass = $cell->getContentClass();
            foreach ($this->controls as $name) {
                $controlId = Session::encrypt(
                    message: json_encode(['i' => $name]),
                    nonce  : substr(md5(ltrim($containerClass, '\\').$name), 0, 24));
                $action['tb'][$cell->containerId()][$cell->cellId()]['m'][$controlId][$this->modification] = $this->modificationValue;
            }
        }
        $action['state'] = 2;
        return new ActionResultMigrationHelper($action);
    }
}
