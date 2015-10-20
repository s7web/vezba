<?php

namespace S7D\Vendor\Blog\Entity;

/**
 * Class Tag
 * @package S7D\Vendor\Blog\Entity
 *
 * @MappedSuperclass
 */
class Tag
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;
}