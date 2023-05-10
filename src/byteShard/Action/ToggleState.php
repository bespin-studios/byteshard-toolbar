<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Action;

use byteShard\Exception;
use byteShard\Internal\Action;
use byteShard\Internal\Action\ActionResultInterface;
use byteShard\Internal\Session;
use byteShard\Toolbar\Control\TwoStateButton\State;

/**
 * Class ToggleState
 * @package byteShard\Action
 */
class ToggleState extends Action
{
    /**
     * @var State
     */
    private State $state;

    /**
     * ToggleState constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        parent::__construct();
        $this->state = $state;
    }

    protected function runAction(): ActionResultInterface
    {
        $id = $this->getLegacyId();
        if (array_key_exists('state', $id)) {
            if (($_SESSION[MAIN] instanceof Session) && is_bool($id['state'])) {
                $this->state->storeState($id['state'], $_SESSION[MAIN]->getUserID());
            } else {
                $e = new Exception(__METHOD__.": Array key state must be boolean. Input was '".gettype($id['state'])."'");
                $e->setLocaleToken('byteShard.action.toggleState.invalidArgument.runAction.id_state');
                throw $e;
            }
        } else {
            $e = new Exception(__METHOD__.": array key state doesn't exist");
            $e->setLocaleToken('byteShard.action.toggleState.invalidArgument.runAction.id');
            throw $e;
        }
        return new Action\ActionResult();
    }
}
