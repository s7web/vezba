<?php

namespace S7D\Core\Acl;

/**
 * Class Acl
 *
 * Used to perform Access Control checks
 * @package Acl
 */
class Acl
{

    /**
     * Defined acl list
     * @var array
     */
    private $aclList;

    /**
     * Current user role taken from session
     * @var string
     */
    private $currentUserRole;

    /**
     * Minimum role needed to access resource
     * @var string
     */
    private $minRoleToAccess;

    /**
     * Set Acl list for check
     *
     * @param array $aclList
     *
     * @return void
     */
    public function setAclList(array $aclList)
    {
        $this->aclList = $aclList;
    }

    /**
     * Set current user to check
     *
     * @param string $currentUserRole
     *
     * @return void
     */
    public function setCurrentUserRole($currentUserRole)
    {
        $this->currentUserRole = $currentUserRole;
    }

    /**
     * Set minimum role to access resource
     *
     * @param string $minRoleToAccess
     *
     * @return void
     */
    public function setMinRoleToAccess($minRoleToAccess)
    {
        $this->minRoleToAccess = $minRoleToAccess;
    }

    /**
     * Perform check does user have enough permissions to access resource
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function performCheck()
    {
        if(empty($this->aclList)){
            throw new \Exception("Acl list is empty, you must have least one role defined!");
        }

        if ( $this->currentUserRole !== $this->minRoleToAccess && ! in_array($this->minRoleToAccess, $this->aclList[$this->currentUserRole])) {
            return false;
        }

        return true;
    }

}