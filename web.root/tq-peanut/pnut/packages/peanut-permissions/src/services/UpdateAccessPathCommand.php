<?php

namespace Peanut\PeanutPermissions\services;

use Peanut\PeanutPermissions\db\AccessPathManager;
use Tops\services\TServiceCommand;

class UpdateAccessPathCommand extends TServiceCommand
{
    protected function run()
    {
        $request = $this->getRequest();
        if (!$request) {
            $this->addErrorMessage('Invalid request');
            return;
        }
        $path = $request->path ?? null;
        if (!$path) {
            $this->addErrorMessage('No path received');
            return;
        }
        $action = $request->action ?? 'update';
        if ($action != 'update' && $action != 'delete') {
            $this->addErrorMessage('Invalid action: '.$action);
            return;
        }

        $manager = new AccessPathManager();
        if ($action == 'update') {
            $roleNames = $request->roleNames ?? null;
            if (!$roleNames) {
                $this->addErrorMessage('No role names received');
                return;
            }
            if (!is_array($roleNames)) {
                $roleNames = explode(',',$roleNames);
            }
            $manager->updatePath($path,$roleNames);
        }
        else {
            $manager->deletePath($path);
        }
        $response = $manager->getAccessPathList();
        $this->setReturnValue($response);
    }
}