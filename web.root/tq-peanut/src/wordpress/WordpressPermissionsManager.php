<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 9/12/2017
 * Time: 7:29 AM
 */

namespace Tops\wordpress;

// require_once(\Tops\sys\TPath::getFileRoot().'/wp-admin/includes/user.php');

use Tops\db\model\repository\PermissionsRepository;
use Tops\db\TDBPermissionsManager;
use Tops\sys\TPermissionsManager;
use Tops\sys\TStrings;
use function wp_roles;

class WordpressPermissionsManager extends TDBPermissionsManager
{
    const handleFormat = TStrings::keyFormat;
    /**
     * @var PermissionsRepository
     */
    private $repository;

    public function getRoleHandleFormat()
    {
        return self::handleFormat;
    }

    public function getPermissionHandleFormat() {
        // native identifier
        return self::handleFormat;
    }

    private function getRepository()
    {
        if (!isset($this->repository)) {
            $this->repository = new PermissionsRepository();
        }
        return $this->repository;
    }

    /**
     * @param string $roleName
     * @param null $roleDescription
     * @return bool
     */
    public function addRole($roleName,$roleDescription=null)
    {
        if ($roleName == $this->getGuestRole() || $roleName == $this->getAuthenticatedRole()) {
            return true;
        }
        $roleHandle = $this->formatRoleHandle($roleName);
        $existing = $this->getWpRole($roleHandle);
        if (!empty($existing)) {
            return false;
        }
        if ($roleDescription === null) {
            $roleDescription = $roleName;
        }
        $roleDescription = $this->formatRoleDescription($roleDescription);
        $result = wp_roles()->add_role($roleHandle, __($roleDescription), array('read' => true));
        return $result !== null;
    }

    /**
     * @param string $roleName
     * @return bool
     */
    public function removeRole($roleName)
    {
        if ($roleName == $this->getGuestRole() || $roleName == $this->getAuthenticatedRole()) {
            return true;
        }
        $roleName = $this->formatRoleHandle($roleName);
        $wpRoles = wp_roles();
        $role = $this->getWpRole($roleName);
        if( !empty($role)){
            $wpRoles->remove_role($roleName);
            return true;
        }
        return false;
    }

    /**
     * @return \stdClass[]
     */
    public function getRoles()
    {
        $result = $this->getActualRoles(false);
        $virtualRoles = $this->getVirtualRoles();
        $result[] = $virtualRoles[self::authenticatedRole];
        $result[] = $virtualRoles[self::guestRole];

        return $result;
    }

    // private

    public function getActualRoles($useWpFormat = true) {
        $result = array();
        $roleObjects =  wp_roles()->roles;
        unset($roleObjects['administrator']);
        foreach ($roleObjects as $roleName => $roleObject) {
            $item = $this->createRoleObject($roleName,$roleObject['name']);
            if ($useWpFormat) {
                $item->Key = TStrings::ConvertNameFormat($item->Key,self::handleFormat);
            }
            $result[] = $item;
        }
        return $result;
    }

    private function getWpRole($roleName) {
        $roleHandle = $this->formatRoleHandle($roleName);
        return wp_roles()->get_role($roleHandle);
    }

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
    public function assignPermission($roleName, $permissionName)
    {
        if ($roleName == $this->getGuestRole() || $roleName == $this->getAuthenticatedRole()) {
            return $this->getRepository()->assignPermission($roleName, $permissionName);
        }
        $role = $this->getWpRole($roleName);
        $permissionKey = TStrings::convertNameFormat($permissionName, self::$permissionKeyFormat);
        $role->add_cap($permissionKey);
        return true;
    }
     */

    /**
     * @param string $roleName
     * @param string $permissionName
     * @return bool
    public function revokePermission($roleName, $permissionName)
    {
        if ($roleName == $this->getGuestRole() || $roleName == $this->getAuthenticatedRole() ) {
            return $this->getRepository()->revokePermission($roleName,$permissionName);
        }
        $role = $this->getWpRole($roleName);
        $permissionKey = TStrings::convertNameFormat($permissionName,self::$permissionKeyFormat);
        $role->remove_cap($permissionKey);
        return true;
    }

    public function removePermission($name)
    {
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            $this->revokePermission($role->Value,$name);
        }
    }

    public function verifyPermission($permissionName)
    {
        $permissionKey = TStrings::convertNameFormat($permissionName,self::$permissionKeyFormat);
        return current_user_can($permissionKey);
    }
     */
}