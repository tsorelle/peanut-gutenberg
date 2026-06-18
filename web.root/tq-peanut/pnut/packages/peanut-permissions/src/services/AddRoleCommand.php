<?php

namespace Peanut\PeanutPermissions\services;

use Tops\services\TServiceCommand;
use Tops\sys\TPermissionsManager;

class AddRoleCommand extends TServiceCommand
{

    protected function run()
    {
        $roleName = $this->getRequest();
        if (empty($roleName)) {
            $this->addErrorMessage('Role name cannot be empty');
            return;
        }
        $manager= TPermissionsManager::getPermissionManager();
        $manager->addRole($roleName);
        $roles = $manager->getRoles();
        // todo: test this, is admin needed here?
        unset($roles['administrator']);
        $this->setReturnValue($roles);
    }
}