<?php

namespace Peanut\PeanutPermissions\services;

use Peanut\PeanutPermissions\db\AccessPathManager;
use Tops\services\TServiceCommand;
use Tops\sys\TObjectContainer;
use Tops\sys\TSystemEvents;

class GetAccessPathListCommand extends TServiceCommand
{
    protected function run()
    {
       $manager = new AccessPathManager();
       $response = new \stdClass();
       $response->paths = $manager->getAccessPathList();
       $response->roles = $manager->getRoleNames();
       $def = TObjectContainer::HasDefinition(TSystemEvents::HANDLER_CLASS_KEY);
       $response->finalize =
           TObjectContainer::HasDefinition(TSystemEvents::HANDLER_CLASS_KEY) ?
               TSystemEvents::ON_AUTHORIZATIONS_CHANGED : null;
       $this->setReturnValue($response);
    }
}