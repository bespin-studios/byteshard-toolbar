<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control;

use byteShard\Internal\Toolbar;

/**
 * Class SelectButton
 * @package byteShard\CellContent\Toolbar
 */
class SelectButton extends Toolbar\ToolbarObject
{
    use Toolbar\Disabled;
    use Toolbar\Img;
    use Toolbar\ImgDisabled;
    use Toolbar\Nested;
    use Toolbar\MaxOpen;
    use Toolbar\OpenAll;
    use Toolbar\RenderSelect;
    use Toolbar\Text;
    use Toolbar\Tooltip;
    use Toolbar\Width;

    protected string $type = 'buttonSelect';

    public function __construct(string $id)
    {
        parent::__construct($id);
        $this->img    = $id.'.png';
        $this->imgDis = $id.'_dis.png';
    }
}
