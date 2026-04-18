<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:17 AM
 */

namespace Tops\wordpress;

use Tops\sys\TAbstractUser;
use Tops\sys\TImage;
use Tops\sys\TPermissionsManager;
use Tops\sys\TStrings;
use Tops\sys\TUser;
use WP_User;

/**
 * Class TConcrete5User
 * @package Tops\sys
 *
 */
class TWordpressUser extends TAbstractUser
{
    const WordpressAdminRole = 'administrator';
    const WordpressGuestRole = 'guest';

     /**
     * @var $user WP_User
     */
    private $user;


    public function getUser() {
        if (isset($this->user) && $this->user !== null) {
            return $this->user->exists() ? $this->user : false;
        }
        return false;
    }



    // overrides base method
    public function getProfileValue($key)
    {
        $result = parent::getProfileValue($key);
        $key = $this->formatProfileKey($key);
        if ($result !== false) {
            $user = $this->getUser();
            if ($user !== false) {
                $wpKey = $this->formatPermissionHandle($key);
                    // TStrings::ConvertNameFormat($key,TStrings::keyFormat);
                if ($user->has_prop($wpKey)) {
                    return $user->get($wpKey);
                }
            }
        }
        return empty($result) ? '' : $result;

    }

    public function setUser(WP_User $user) {
        unset($this->profile);
        if (!empty($user) && $user->exists()) {
            $this->user = $user;
            $this->userName = $user->user_login;
            $this->id = $user->ID;
        }
        else {
            unset($this->user);
        }
        $this->updateLanguage();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function loadById($id)
    {
        $user = get_userdata($id);
        $this->setUser($user);

    }

    /**
     * @param $userName
     * @return mixed
     */
    public function loadByUserName($userName)
    {
         $user = get_user_by('login',$userName);
         $this->setUser($user);
    }



    /**
     * @return mixed
     */
    public function loadCurrentUser()
    {
        $user = wp_get_current_user();
        $this->setUser($user);
        $this->isCurrentUser = true;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        $roles = $this->getRoles();
        return (in_array(self::WordpressAdminRole,$roles));
    }

    private $roleKeys;
    /**
     * @return string[]
     */
    public function getRoles()
    {
        if (!isset($this->roleKeys)) {
            $manager = TPermissionsManager::getPermissionManager();
            $user = $this->getUser();
            if ($user === false) {
                $this->roleKeys = array($manager->getGuestRole());
            }
            else {
                $this->roleKeys = TPermissionsManager::toKeyArray($user->roles);
                $this->roleKeys[] = $manager->getAuthenticatedRole();
            }
        }
        return $this->roleKeys;
    }

    /**
     * @param $roleName
     * @return bool
     */
    public function isMemberOf($roleName)
    {
        $result = parent::isMemberOf($roleName);
        if (!$result) {
            $roleName = $this->formatKey($roleName);
            $roles = $this->getRoles();
            if (in_array($roleName,$roles)) {
                return true;
            };
            return (in_array(self::WordpressAdminRole,$roles));
        }

        return $result;
    }

     /**
     * @param string $value
     * @return bool
     */
    public function isAuthorized($permissionName = '')
    {
        $authorized = parent::isAuthorized($permissionName);
        if (!$authorized) {
             $authorized = $this->checkDbPermission($permissionName);
             if (!$authorized) {
                 $authorized = $this->checkWpPermission($permissionName);
             }
        }
        return $authorized;
    }

    private function checkWpPermission($permissionName) {
        $user = $this->getUser();
        if (empty($user)) {
            return false;
        }
        $permissionName =  $this->formatPermissionHandle($permissionName);
        return $this->user->has_cap($permissionName);
    }

    private function checkDbPermission($permissionName) {
        $permissionName =  $this->formatKey($permissionName);
        $manager = TPermissionsManager::getPermissionManager();
        $permission = $manager->getPermission($permissionName);
        if (empty($permission)) {
            return false;
        }
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            if ($permission->check($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        $user = $this->getUser();
        return ($user !== false);
    }

    protected function loadProfile()
    {
        $user = $this->getUser();
        if (!empty($user)) {
            $this->profile[TUser::profileKeyFullName] = $user->display_name;
            $this->profile[TUser::profileKeyShortName] = $user->user_nicename;
            $this->profile[TUser::profileKeyEmail] = $user->user_email;
        }
    }

    /**
     * @param $email
     * @return void
     */
    public function loadByEmail($email)
    {
        $user = get_user_by('email',$email);
        $this->setUser($user);
    }

    public function isCurrent()
    {
        if (isset($this->isCurrentUser)) {
            return parent::isCurrent();
        }
        if (isset($this->user)) {
            $current_user = wp_get_current_user();
            if ($current_user instanceof WP_User) {
                return $current_user->user_login == $this->user->user_login;
            }
        }
        return false;
    }

    function getUserPicture($size = 0, array $classes = [], array $attributes = [])
    {
        if (!$this->isAuthenticated()) {
            return '';
        }
        switch ($size) {
            case 0 :
                $size = 512;
                break;
            case TImage::sizeResponsive :
                $classes[] = 'image-responsive';
                $size = 512;
                break;
            default :
                if ($size > 512) {
                    $size = 512; // maximum size
                }
        }
        $args = [];

        if (!empty($classes)) {
            $args['class'] = join(' ',$classes);
        }

        $i = get_avatar($this->getId(),$size,$this->getDisplayName(),'',$args);
        return $i;

    }

    public function signIn($username, $password = null)
    {
        $user = wp_signon([
            'user_login' => $username,
            'user_password' => $password
        ],false);
        if (is_wp_error($user)) {
            return false;
        }
        wp_set_current_user($user->ID);
        return true;
    }
}