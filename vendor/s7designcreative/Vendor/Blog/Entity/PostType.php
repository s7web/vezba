<?php

namespace S7D\Vendor\Blog\Entity;

/**]
 * Class PostType
 * @package S7D\Vendor\Blog\Entity
 *
 * @MappedSuperclass
 */
class PostType
{

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") */
    protected $name;
}