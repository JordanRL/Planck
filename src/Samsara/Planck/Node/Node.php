<?php

namespace Samsara\Planck\Node;

use Samsara\Planck\Core\AbstractNode;

class Node extends AbstractNode
{
    /**
     * @var Event[]
     */
    protected $events = [];

    /**
     * @param   string  $key
     * @param   Event   $event
     * @return  $this
     * @throws  \Exception
     */
    public function attachEvent($key, Event $event)
    {
        if (array_key_exists($key, $this->events)) {
            throw new \Exception('Cannot attach node using key '.$key.' as that key is already in use.');
        }

        $this->events[$key] = $event;

        return $this;
    }

    /**
     * @param   string  $key
     * @return  null|Event
     */
    public function getEvent($key)
    {
        if (array_key_exists($key, $this->events)) {
            return $this->events[$key];
        }

        return null;
    }

    /**
     * @param   string  $key
     * @return  $this
     */
    public function removeEvent($key)
    {
        if (array_key_exists($key, $this->events)) {
            unset($this->events[$key]);
        }

        return $this;
    }

    /**
     * @return  array
     */
    public function getAllEventsProperties()
    {
        $properties = [];

        foreach ($this->events as $event) {
            $properties = array_merge($properties, $event->getAllProperties());
        }

        return $properties;
    }
}