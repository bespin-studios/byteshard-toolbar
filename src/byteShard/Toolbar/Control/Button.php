<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Config;
use byteShard\Internal\Toolbar;

/**
 * Class Button
 * @package byteShard\CellContent\Toolbar
 */
class Button extends Toolbar\ToolbarObject
{
    use Toolbar\Disabled;
    use Toolbar\Hidden;
    use Toolbar\Img;
    use Toolbar\ImgDisabled;
    use Toolbar\Text;
    use Toolbar\HideText;
    use Toolbar\Tooltip;

    protected string $type = 'button';

    /**
     * Button constructor.
     * @param string $id
     */
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
        if ($useSVG === true) {
            $this->img    = $id.'.svg';
        } else {
            $this->img    = $id.'.png';
            $this->imgDis = $id.'_dis.png';
        }
    }
}
