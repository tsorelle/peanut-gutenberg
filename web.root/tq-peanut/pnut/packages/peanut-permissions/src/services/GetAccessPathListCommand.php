<?php

namespace Peanut\PeanutPermissions\services;

use Peanut\PeanutPermissions\db\AccessPathManager;
use Tops\services\TServiceCommand;

class GetAccessPathListCommand extends TServiceCommand
{
    protected function run()
    {
       $manager = new AccessPathManager();
       $response = $manager->getAccessPathList();
       $this->setReturnValue($response);
    }
}