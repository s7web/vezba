<?php

namespace S7D\Vendor\Menu\Entity;

/**
 * Class Menu
 * @package S7D\Vendor\Menu\Entity
 *
 * @Entity(repositoryClass="S7D\Vendor\Menu\Repository\MenuRepository") @Table(name="menu")
 */
class Menu
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;
}