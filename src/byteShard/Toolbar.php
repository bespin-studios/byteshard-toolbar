<?php
/**
 * @copyright  Copyright (c) 2009 Bespin Studios GmbH
 * @license    See LICENSE file that is distributed with this source code
 */

namespace byteShard;

use byteShard\Internal\Permission\PermissionImplementation;
use byteShard\Internal\SimpleXML;
use byteShard\Internal\Struct\ClientCellEvent;
use byteShard\Internal\Struct\ContentComponent;
use byteShard\Internal\Struct\UiComponentInterface;
use byteShard\Internal\Toolbar\ToolbarClassInterface;
use byteShard\Internal\Toolbar\ToolbarContainer;
use byteShard\Internal\Toolbar\ToolbarObject;
use byteShard\Toolbar\Control\ButtonWithList;
use byteShard\Toolbar\Control\Calendar;
use byteShard\Toolbar\ToolbarObjectInterface;
use SimpleXMLElement;

/**
 * Class Toolbar
 */
class Toolbar implements ToolbarClassInterface
{
    use PermissionImplementation {
        setAccessType as setToolbarAccessType;
    }

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
    public function __construct(private readonly ToolbarContainer $container)
    {
        $this->setParentAccessType($container->getAccessType());
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
            return new ContentComponent(
                type   : Enum\ContentType::DhtmlxToolbar,
                content: $this->getXML(),
                events : $this->getToolbarEvents(),
                setup  : $this->getToolbarParameters(),
                update : $this->getAdvancedControls()
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

    public function addToolbarObject(ToolbarObjectInterface ...$toolbarObjects): static
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
        $toolbarObject->setBaseLocale($this->container->getScopeLocaleTokenBasedOnNamespace());
        $nonce = $this->container->getNonce();

        if ($toolbarObject->hasEvents() === true && $toolbarObject->getAccessType() === Enum\AccessType::RW) {
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
            if ($name !== 'event_onClick_xlsExportThisCell') {
                $eventName = $encryptedName;
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
                if ($event instanceof Toolbar\Event\OnClick) {
                    if ($this->eventOnClick === false) {
                        $this->eventOnClick = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnEnter) {
                    if ($this->eventOnEnter === false) {
                        $this->eventOnEnter = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnStateChange) {
                    if ($this->eventOnStateChange === false) {
                        $this->eventOnStateChange = true;
                    }
                } elseif ($event instanceof Toolbar\Event\OnValueChange) {
                    if ($this->eventOnValueChange === false) {
                        $this->eventOnValueChange = true;
                    }
                }
            }
        } else {
            $toolbarObject->setEventName(base64_encode(ID::UUID()));
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
