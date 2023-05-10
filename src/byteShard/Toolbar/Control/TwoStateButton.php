<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Config;
use byteShard\Internal\Toolbar;

/**
 * Class TwoStateButton
 * @package byteShard\CellContent\Toolbar
 */
class TwoStateButton extends Toolbar\ToolbarObject
{
    use Toolbar\Img;
    use Toolbar\ImgDisabled;
    use Toolbar\Selected;
    use Toolbar\Text;
    use Toolbar\Tooltip;

    protected string $type = 'buttonTwoState';

    public function __construct(string $id)
    {
        parent::__construct($id);
        $useSVG = false;
        if (class_exists('\\config')) {
            $config = new \config();
            if ($config instanceof Config) {
                $useSVG = $config->useSVG();
            }
        }
        if ($useSVG) {
            $this->img = $id.'.svg';
        } else {
            $this->img    = $id.'.png';
            $this->imgDis = $id.'_dis.png';
        }
    }
}
