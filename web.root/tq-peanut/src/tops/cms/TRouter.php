<?php

namespace Tops\cms;

use Tops\services\ServiceRequestHandler;
use Tops\sys\TObjectContainer;
use Tops\sys\TUser;
use Tops\sys\TWebSite;

abstract class TRouter
{
    /**
     * @throws \Exception
     */
    public static function Execute() : bool {
        self::checkAuthorization();
        $handler = TRouteFinder::$matched['handler'] ?? null;
        switch (TRouteFinder::$matched['handler'] ?? 'notfound') {
            case 'service' :
                self::routeService();
                break;
            case 'page' :
                self::getInstance()->routePage();
                break;
            case 'cms' :
                self::getInstance()->routeCms();
                break;
            case 'notfound' :
                return false;
                //  break;
            default:
                throw new \Exception('Invalid configuration. Must include "handler"');
        }
        return true;
    }

    private static $instance;
    private static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = TObjectContainer::get('peanut.router');
        }
        return self::$instance;
    }

    abstract function routeCms();
    abstract function routePage();
    abstract function redirectToSignIn();

    public static function routeService() : void
    {
        $routeData = TRouteFinder::$matched;
        $method = $routeData['method'] ?? null;
        if (empty($method)) {
            throw new \Exception('Value "method" is required in service routing configuration.');
        }
        $handler = new ServiceRequestHandler();
        $argValues = $routeData['argValues'] ?? [];
        if (!empty($argValues)) {
            $handler->$method(...$argValues);
        }
        else {
            $handler->$method();
        }
        exit;
    }

    private static function checkAuthorization()
    {
        $user = TUser::getCurrent();
        $roleList = TRouteFinder::$matched['roles'] ?? '';
        $roleList = trim($roleList);
        if (!empty($roleList)) {
            $roles = explode(',',$roleList);
            $ok = false;
            foreach ($roles as $role) {
                if ($user->isMemberOf($role)) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                $signInConfig = TRouteFinder::$routes['signin'] ?? null;
                if (empty($signInConfig)) {
                    self::getInstance()->redirectToSignIn();
                }
                else {
                    $signInConfig['uri'] = 'signin';
                    $uri = TRouteFinder::$matched['uri'] ?? '/';
                    $redirect = TWebSite::ExpandUrl($uri);
                    // $_SESSION[AccountManager::returnKey] = $redirect;
                    $signInConfig['return'] = $redirect;
                    TRouteFinder::$matched = $signInConfig;
                }
            }
        }
    }
}