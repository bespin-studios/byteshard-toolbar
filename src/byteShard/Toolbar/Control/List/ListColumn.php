<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Toolbar\Control\List;

class ListColumn
{
    public function __construct(private readonly string $id, private readonly string $text)
    {
    }
    
    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }
}