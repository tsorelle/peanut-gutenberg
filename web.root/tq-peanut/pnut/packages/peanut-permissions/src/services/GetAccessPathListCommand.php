<?php

namespace Peanut\PeanutPermissions\services;

use Peanut\PeanutPermissions\db\AccessPathManager;
use Tops\services\TServiceCommand;

class GetAccessPathListCommand extends TServiceCommand
{
    protected function run()
    {
       $manager = new AccessPathManager();
       $response = new \stdClass();
       $response->paths = $manager->getAccessPathList();
       $response->roles = $manager->getRoleNames();
       $this->setReturnValue($response);
    }
}