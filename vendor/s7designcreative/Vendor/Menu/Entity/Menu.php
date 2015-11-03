<?php

namespace S7D\Vendor\Menu\Entity;

/**
 * Class Menu
 * @package S7D\Vendor\Menu\Entity
 *
 * @Entity @Table(name="menu")
 */
class Menu
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;

    /** @Column(type="integer") */
    protected $location;

    /** @Column(type="json_array") */
    protected $items;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return ($this->items != '' && $this->items != ' ' && $this->items != null) ? json_decode($this->items) : array();
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

}