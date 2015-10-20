<?php

namespace S7D\Vendor\Blog\Entity;

/**
 * Class PostStatus
 * @package S7D\Vendor\Blog\Entity
 *
 * @MappedSuperclass
 */
class PostStatus
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;
}