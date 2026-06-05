<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/3/2019
 * Time: 7:55 AM
 */

namespace Peanut\sys;


use Tops\sys\TObjectContainer;
use Tops\sys\TPermissionsManager;
use Tops\sys\TStrings;
use Tops\sys\TUser;

abstract class TVmContext
{
    /**
     * @var TVmContext
     */
    private static $instance;


    public static function GetContext($contextId) {
        if (!isset(self::$instance)) {
            self::$instance = TObjectContainer::Get('peanut.vmcontext');
        }
        if (self::$instance) {
            return self::$instance->get($contextId);
        }
        return self::getNullContext();
    }

    protected abstract function getBlockData($blockId) : \stdClass;

    protected function isInRole($role) {
        $user = TUser::getCurrent();
        if ($role == TPermissionsManager::guestRole) {
            return !$user->isAuthenticated();
        }
        return $user->isMemberOf($role);
    }

    protected function getRoleSelection($value)
    {
        if (empty($value)) {
            return '';
        }
        $parts = explode('?', $value);
        if (sizeof($parts) > 1) {
            $role = $parts[0];
            list($yes, $no) = TStrings::Split($parts[1],':',2,'');
            return  $this->isInRole($role) ? $yes : $no;
        }
        return $parts[0];
    }

    protected function get($contextId)
    {
        $result = self::getNullContext();
        if (!empty($contextId)) {
            $value = null;

            // separate block id & default
            $parts = explode('&',$contextId);
            $blockId = array_shift($parts);

            // join remainder in case default value contains literal ampersand
            $shared = implode('&',$parts);

            $blockData = $this->getBlockData($blockId);

            // check for role based data
            //  e.g.  admin?specialvalue
            $result->value = $this->getRoleSelection($value);
            $result->shared = $this->getRoleSelection($shared);
            if (empty($result->value)) {
                $result->value = $result->shared;
            }
        }
        return $result;
    }

    protected static function getNullContext() {
        $result = new \stdClass();
        $result->viewmodel = '';
        $result->value = '';
        $result->shared = '';
        return $result;
    }


}