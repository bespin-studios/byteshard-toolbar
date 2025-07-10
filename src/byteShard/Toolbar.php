<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard;

use byteShard\Enum;
use byteShard\Internal\Permission\PermissionImplementation;
use byteShard\Internal\SimpleXML;
use byteShard\Internal\Struct\ClientCellComponent;
use byteShard\Internal\Struct\ClientCellEvent;
use byteShard\Internal\Struct\UiComponentInterface;
use byteShard\Internal\Toolbar\ToolbarContainer;
use byteShard\Internal\Toolbar\ToolbarObject;
use byteShard\Toolbar\Control\ButtonWithList;
use byteShard\Toolbar\Control\Calendar;
use byteShard\Toolbar\ToolbarInterface;
use byteShard\Toolbar\ToolbarObjectInterface;
use SimpleXMLElement;

/**
 * Class Toolbar
 */
class Toolbar implements ToolbarInterface
{
    use PermissionImplementation {
        setAccessType as setToolbarAccessType;
    }

    private ToolbarContainer $container;
    /** @var ToolbarObject[] */
    private array  $toolbarObjects = [];
    private array  $lists          = [];
    private string $outputCharset  = 'utf-8';
    private string $exportId;

    // Events
    private bool $eventOnClick       = false;
    private bool $eventOnStateChange = false;
    private bool $eventOnValueChange = false;
    private bool $eventOnEnter       = false;

    /**
     * Toolbar constructor.
     * @param ToolbarContainer $container
     * @throws Exception
     */
    public function __construct(ToolbarContainer $container)
    {
        $this->container = $container;
        $this->setParentAccessType($container->getAccessType());
        if ($container instanceof Tab) {
            // TODO: if method exists else exception
            // don't abstract that function as it only applies to Tab\Toolbars and not Cell\Toolbars
            if (method_exists($this, 'defineToolbarContent')) {
                $this->defineToolbarContent();
            }
        }
    }

    /**
     * Do not close session here because the toolbar is usually called within a cell which might still need the session
     * @return array
     * @throws Exception
     */
    public function getContents(): array
    {
        trigger_error('Toolbar::getContents has been deprecated in favour of getComponent', E_USER_DEPRECATED);
        if ($this->getAccessType() > Enum\AccessType::NONE && !empty($this->toolbarObjects)) {
            $this->evaluateToolbarObjects();
            return [
                'toolbar'           => true,
                'toolbarAdvanced'   => $this->getAdvancedControls(),
                'toolbarContent'    => $this->getXML(),
                'toolbarEvents'     => $this->getToolbarEvents(),
                'toolbarParameters' => $this->getToolbarParameters()
            ];
        } else {
            return ['toolbar' => false];
        }
    }

    public function getComponent(): ?UiComponentInterface
    {
        if ($this->getAccessType() > Enum\AccessType::NONE && !empty($this->toolbarObjects)) {
            $this->evaluateToolbarObjects();
            return new ClientCellComponent(
                type   : Enum\ContentType::DhtmlxToolbar,
                content: $this->getXML(),
                events : $this->getToolbarEvents(),
                pre    : $this->getToolbarParameters(),
                post   : $this->getAdvancedControls()
            );
        }
        return null;
    }

    private function getAdvancedControls(): array
    {
        $result = [];
        foreach ($this->toolbarObjects as $toolbarObject) {
            if ($toolbarObject instanceof Calendar) {
                if ($toolbarObject->showTime() === false) {
                    $result['calendar'][$toolbarObject->getId()] = ['hideTime' => true];
                }
            }
        }
        return $result;
    }

    public function addToolbarObject(ToolbarObjectInterface ...$toolbarObjects): self
    {
        foreach ($toolbarObjects as $toolbarObject) {
            if ($toolbarObject instanceof ToolbarObject) {
                $this->toolbarObjects[] = $toolbarObject;
            }
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    private function evaluateToolbarObjects(): void
    {
        foreach ($this->toolbarObjects as $toolbarObject) {
            $this->evaluateToolbarObject($toolbarObject);
        }
    }

    /**
     * @param ToolbarObject $toolbarObject
     * @throws Exception
     */
    private function evaluateToolbarObject(ToolbarObject $toolbarObject): void
    {
        $toolbarObject->setParentAccessType($this->getAccessType());
        $nonce = '';
        if ($this->container instanceof Cell) {
            $toolbarObject->setBaseLocale($this->container->createLocaleBaseToken('Cell'));
            $nonce = $this->container->getNonce();
        } elseif ($this->container instanceof Tab) {
            //TODO: tab nonce
            $toolbarObject->setBaseLocale($this->container->getToolbarName());
        }

        if ($toolbarObject->hasEvents() === true && $toolbarObject->getAccessType() === Enum\AccessType::RW) {
            //TODO: don't register event and ID in session if the event doesn't have any actions assigned to it
            // set Events only for objects which can be accessed by user
            $name        = $toolbarObject->getToolbarObjectName();
            $objectNonce = substr(md5($nonce.$name), 0, 24);

            $toolbarObjectClass = $toolbarObject::class;
            // abbreviate framework controls to keep object ids as short as possible
            if (str_starts_with($toolbarObjectClass, 'byteShard\\Toolbar\\Control\\')) {
                $toolbarObjectClass = '!t'.substr($toolbarObjectClass, 26);
            }

            $encrypted     = [
                'i' => $name,
                'a' => $toolbarObject->getAccessType(),
                't' => $toolbarObjectClass
            ];
            $encryptedName = Session::encrypt(json_encode($encrypted), $objectNonce);
            $eventName     = '';
            if ((($this->container instanceof Cell) || ($this->container instanceof Tab)) && $name !== 'event_onClick_xlsExportThisCell') {
                $tmp       = $this->container->getEventIDForInteractiveObject($name, true, $encryptedName);
                $eventName = $tmp['name'];
                $toolbarObject->setEventName($eventName);
            }
            if ($toolbarObject instanceof ButtonWithList) {
                $list = $toolbarObject->getList($nonce);
                if (!empty($list) && array_key_exists('fields', $list) && array_key_exists('values', $list)) {
                    $this->lists[$eventName] = $list;
                }
            }
            $tmpEvents = $toolbarObject->getEvents();
            foreach ($tmpEvents as $event) {
                $actions = $event->getActionArray();
                if ($this->container instanceof Cell) {
                    foreach ($actions as $action) {
                        $action->initActionInCell($this->container);
                    }
                } elseif ($this->container instanceof Tab) {
                    foreach ($actions as $action) {
                        $action->initActionInTab($this->container);
                    }
                }
                $valid = false;
                if ($event instanceof Toolbar\Event\OnClick) {
                    $valid = true;
                    if ($this->eventOnClick === false) {
                        $this->eventOnClick = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnEnter) {
                    $valid = true;
                    if ($this->eventOnEnter === false) {
                        $this->eventOnEnter = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnStateChange) {
                    $valid = true;
                    if ($this->eventOnStateChange === false) {
                        $this->eventOnStateChange = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnValueChange) {
                    $valid = true;
                    if ($this->eventOnValueChange === false) {
                        $this->eventOnValueChange = true;
                    }
                }
                if ($valid === true && $eventName !== '' && (($this->container instanceof Cell) || ($this->container instanceof Tab))) {
                    $this->container->setEventForInteractiveObject($name, $event);
                }
            }
        }
        if (property_exists($toolbarObject, 'nestedItems')) {
            foreach ($toolbarObject->nestedItems as $nestedObject) {
                $this->evaluateToolbarObject($nestedObject);
            }
        }
    }

    private function getToolbarEvents(): array
    {
        $toolbarEvents = [];
        //TODO: why set some events when parent_access_type is readonly?
        //Why test parent access type? Should be this->getAccessType()
        //TODO: implement unrestricted access. Not necessary if getAccessType will be used instead
        if ($this->getAccessType() === Enum\AccessType::RW) {
            if ($this->eventOnClick === true) {
                $toolbarEvents[] = new ClientCellEvent('onClick', 'doOnClick');
            }
            if ($this->eventOnEnter === true) {
                $toolbarEvents[] = new ClientCellEvent('onEnter', 'doOnEnter');
            }
            if ($this->eventOnStateChange === true) {
                $toolbarEvents[] = new ClientCellEvent('onStateChange', 'doOnStateChange');
            }
            if ($this->eventOnValueChange === true) {
                $toolbarEvents[] = new ClientCellEvent('onValueChange', 'doOnValueChange');
            }
        } elseif ($this->getAccessType() === Enum\AccessType::R) {
            $toolbarEvents[] = new ClientCellEvent('onStateChange', 'doOnStateChange');
            $toolbarEvents[] = new ClientCellEvent('onClick', 'doOnClick');
            $toolbarEvents[] = new ClientCellEvent('onEnter', 'doOnEnter');
        }
        if ($toolbarEvents === null && ($this->container instanceof Tab)) {
            $toolbarEvents[] = new ClientCellEvent('onClick', 'doOnClick');
        }
        return $toolbarEvents;
    }

    private function getToolbarParameters(): array
    {
        $parameters = [];
        if (!empty($this->lists)) {
            $parameters['lists'] = $this->lists;
        }
        return $parameters;
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    private function getXML(): string
    {
        SimpleXML::initializeDecode();
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="'.$this->outputCharset.'" ?><toolbar/>');
        foreach ($this->toolbarObjects as $toolbarObject) {
            if ($toolbarObject->getAccessType() > Enum\AccessType::NONE) {
                $this->addToolbarObjectsToXML($toolbarObject, $xml);
            }
        }
        return SimpleXML::asString($xml);
    }

    /**
     * @throws Exception
     */
    private function addToolbarObjectsToXML(ToolbarObject $toolbarObject, SimpleXMLElement $xml): void
    {
        $item                 = $xml->addChild('item');
        $toolbarObjectContent = $toolbarObject->getContents();
        foreach ($toolbarObjectContent as $name => $value) {
            SimpleXML::addAttribute($item, $name, $value);
        }
        if (property_exists($toolbarObject, 'nestedItems')) {
            foreach ($toolbarObject->nestedItems as $nestedObject) {
                $this->addToolbarObjectsToXML($nestedObject, $item);
            }
        }
    }

    /**
     * TODO: generic export for tree
     * @param string $contentId
     * @return array
     */
    public function getXlsExport(string $contentId): array
    {
        $this->exportId = $contentId;
        return $this->defineXlsExport();
    }

    /**
     * @return array
     */
    protected function defineXlsExport(): array
    {
        return [];
    }
}
