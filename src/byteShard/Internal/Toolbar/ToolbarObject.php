<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard\Internal\Toolbar;

use byteShard\Enum;
use byteShard\Internal\Config;
use byteShard\Internal\Event\Event;
use byteShard\Internal\Permission\PermissionImplementation;
use byteShard\Locale;
use byteShard\Toolbar\ToolbarObjectInterface;
use byteShard\Utils\Strings;

/**
 * Class ToolbarObject
 * @package byteShard\Internal\Toolbar
 */
abstract class ToolbarObject implements ToolbarObjectInterface
{
    use PermissionImplementation;

    /** @var string override the type in each control type */
    protected string $type;
    private string   $id;
    private string   $baseLocale = '';
    /** @var Event[] */
    private array $events = [];
    /** @var string */
    private string $eventId = '';

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function hasEvents(): bool
    {
        return !empty($this->events);
    }

    /**
     * @return string
     * @internal
     */
    public function getToolbarObjectName(): string
    {
        return $this->id;
    }

    public function setBaseLocale(string $baseLocale): void
    {
        $this->baseLocale = $baseLocale;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        if (!empty($this->eventId)) {
            return $this->eventId;
        }
        return $this->id;
    }

    /**
     * @return array
     * @internal
     */
    public function getContents(): array
    {
        $object = [];
        if (isset($this->type)) {
            $useSVG         = false;
            $convertToLower = false;
            if (class_exists('\\config')) {
                $config = new \config();
                if ($config instanceof Config) {
                    $useSVG         = $config->useSVG();
                    $convertToLower = $config->convertImageNamesToLowerCase();
                }
            }

            $object['type'] = $this->type;
            if (property_exists($this, 'eventId') && !empty($this->eventId)) {
                $object['id'] = $this->eventId;
            } elseif (property_exists($this, 'id') && !empty($this->id)) {
                $object['id'] = $this->id;
            }
            if (property_exists($this, 'disabled') && ($this->disabled === true || $this->getAccessType() === Enum\AccessType::R)) {
                $object['enabled'] = false;
                if ((!property_exists($this, 'imgDis') || empty($this->imgDis)) && (property_exists($this, 'img') && !empty($this->img))) {
                    if ($useSVG === true && strtolower(substr($this->img, -4)) === '.svg') {
                        $object['imgdis'] = $convertToLower ? strtolower($this->img) : $this->img;
                    } else {
                        $prefix           = substr($this->img, 0, strlen($this->img) - 4);
                        $suffix           = substr($this->img, strlen($this->img) - 4);
                        $object['imgdis'] = $convertToLower ? strtolower($prefix.'_dis'.$suffix) : $prefix.'_dis'.$suffix;
                    }
                }
            }
            if (property_exists($this, 'hidden') && $this->hidden === true) {
                $object['hidden'] = 'true';
            }
            if (property_exists($this, 'img') && !empty($this->img)) {
                $object['img'] = $convertToLower ? strtolower($this->img) : $this->img;
            }
            if (property_exists($this, 'imgDis') && !empty($this->imgDis)) {
                $object['imgdis'] = $convertToLower ? strtolower($this->imgDis) : $this->imgDis;
            }
            if (property_exists($this, 'length') && !empty($this->length)) {
                $object['length'] = $this->length;
            }
            if (property_exists($this, 'maxOpen') && !empty($this->maxOpen)) {
                $object['maxOpen'] = $this->maxOpen;
            }
            if (property_exists($this, 'openAll') && $this->openAll === true) {
                $object['openAll'] = 'true';
            }
            if (property_exists($this, 'renderSelect') && $this->renderSelect === false) {
                $object['renderSelect'] = 'false';
            }
            if (property_exists($this, 'selected') && $this->selected === true) {
                $object['selected'] = 1;
            }
            if (property_exists($this, 'text')) {
                if ($this->text === '' && !empty($this->id) && (!property_exists($this, 'hideText') || $this->hideText === false)) {
                    $object['text'] = Strings::purify(Locale::get($this->baseLocale.'.Toolbar.'.$this->id.'.Label'));
                } elseif ($this->text !== '') {
                    $object['text'] = Strings::purify($this->text);
                }
            }
            if (property_exists($this, 'textMin') && !empty($this->textMin)) {
                $object['textMin'] = $this->textMin;
            }
            if (property_exists($this, 'textMax') && !empty($this->textMax)) {
                $object['textMax'] = $this->textMax;
            }
            if (property_exists($this, 'tooltip') && property_exists($this, 'tooltipType') && !empty($this->tooltip)) {
                $object[$this->tooltipType] = $this->tooltip;
            }
            if (property_exists($this, 'value') && !empty($this->value)) {
                $object['value'] = $this->value;
            }
            if (property_exists($this, 'valueMin') && !empty($this->valueMin)) {
                $object['valueMin'] = $this->valueMin;
            }
            if (property_exists($this, 'valueMax') && !empty($this->valueMax)) {
                $object['valueMax'] = $this->valueMax;
            }
            if (property_exists($this, 'valueNow') && !empty($this->valueNow)) {
                $object['valueNow'] = $this->valueNow;
            }
            if (property_exists($this, 'width') && !empty($this->width)) {
                $object['width'] = $this->width;
            }
        }
        return $object;
    }

    /**
     * @param Event ...$events
     */
    public function addEvents(Event ...$events): self
    {
        foreach ($events as $event) {
            if (!in_array($event, $this->events, true)) {
                $this->events[] = $event;
            }
        }
        return $this;
    }

    /**
     * @return Event[]
     * @internal
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @param string $eventName
     * @internal
     */
    public function setEventName(string $eventName): void
    {
        $this->eventId = $eventName;
    }

    /**
     * @param string $name
     * @internal
     */
    public function setName(string $name): void
    {
        // deprecated
        $this->id = $name;
    }
}
