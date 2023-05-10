<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control\TwoStateButton;

use byteShard\Cell;
use byteShard\Database;
use byteShard\Exception;
use byteShard\Internal\CellContent;
use byteShard\Session;

class State
{
    private string $tabName;
    private string $cellName;
    private string $itemName;
    private string $typeName;

    /**
     * State constructor.
     * @param CellContent|Cell $cell
     * @param string $itemName
     */
    public function __construct(CellContent|Cell $cell, string $itemName)
    {
        $this->typeName = 'Toolbar\TwoStateButton\State';
        if ($cell instanceof CellContent) {
            $cell = $cell->getCell();
        }
        if ($cell instanceof Cell) {
            $this->cellName = (string)$cell->getID();
            $this->tabName  = $cell->getName();
        }
        $this->itemName = $itemName;
    }

    /**
     * @param int|null $userId
     * @return bool
     * @throws Exception
     */
    public function getState(int $userId = null): bool
    {
        if ($userId === null) {
            $userId = Session::getUserId();
            if ($userId === null) {
                $e = new Exception(__METHOD__.': No userId was passed and could not determine the userId from the Session.');
                $e->setLocaleToken('byteShard.toolbar.control.twoStateButton.state.invalidArgument.getState.userId');
                throw $e;
            }
        }
        $tmp = Database::getSingle(
            "SELECT Value
                FROM tbl_User_Settings
                WHERE User_ID=:userId AND Tab=:tab AND Cell=:cell AND Type=:type AND Item=:item", [
            'userId' => $userId,
            'tab'    => $this->tabName,
            'cell'   => $this->cellName,
            'type'   => $this->typeName,
            'item'   => $this->itemName,
        ]);
        if ($tmp !== null && property_exists($tmp, 'Value') && (int)$tmp->Value === 1) {
            return true;
        }
        return false;
    }

    /**
     * @param bool $state
     * @param int|null $userId
     * @throws Exception
     * @internal
     */
    public function storeState(bool $state, int $userId = null): void
    {
        if ($userId === null) {
            $userId = Session::getUserId();
            if ($userId === null) {
                $e = new Exception(__METHOD__.': No userId was passed and could not determine the userId from the Session.');
                $e->setLocaleToken('byteShard.toolbar.control.twoStateButton.state.invalidArgument.storeState.userId');
                throw $e;
            }
        }
        $parameters = [
            'userId' => $userId,
            'tab'    => $this->tabName,
            'cell'   => $this->cellName,
            'type'   => $this->typeName,
            'item'   => $this->itemName,
        ];

        $tmp = Database::getSingle(
            'SELECT Value
                FROM tbl_User_Settings
                WHERE User_ID=:userId AND Tab=:tab AND Cell=:cell AND Type=:type AND Item=:item', $parameters);

        $parameters['value'] = intval($state);
        if ($tmp === null) {
            Database::insert('INSERT INTO tbl_User_Settings (User_ID, Tab, Cell, Type, Item, Value) VALUES (:userId,:tab,:cell,:type,:item,:value)', $parameters);
        } else {
            Database::update('UPDATE tbl_User_Settings SET Value=:value WHERE User_ID=:userId AND Tab=:tab AND Cell=:cell AND Type=:type AND Item=:item', $parameters);
        }
    }
}
