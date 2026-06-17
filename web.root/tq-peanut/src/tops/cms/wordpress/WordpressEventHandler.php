<?php

namespace Tops\cms\wordpress;

use Tops\sys\ISystemEventHandler;
use Tops\sys\TConfiguration;
use Tops\sys\TSystemEvents;

class WordpressEventHandler implements ISystemEventHandler
{

    public function handleEvent($event, $data = null) : bool
    {

        switch ($event) {
            case TSystemEvents::ON_AUTHORIZATIONS_CHANGED:
                $menuName = TConfiguration::getValue('menu-name', 'wordpress', $data);
                $builder = new WordPressSiteMapBuilder($menuName);
                $result = $builder->build();
                return !empty($result->success);
            default:
                error_log('Unsupported event: ' . $event);
                return false;
        }
    }
}