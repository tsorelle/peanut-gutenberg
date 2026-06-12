<?php

namespace Peanut\PeanutPermissions;

use PDO;
use Peanut\PeanutPermissions\db\AccessPathManager;
use Peanut\PeanutPermissions\db\model\repository\AccessPathsRepository;
use Tops\db\TQuery;
use Tops\sys\TUser;
use Tops\sys\TWebSite;

class TAuthorizationPaths
{
    const ACCESS_LIST_SESSIONKEY = 'pnut_accesspath_list';

    public static function ClearCache()
    {
        unset($_SESSION[self::ACCESS_LIST_SESSIONKEY]);
    }

    private static TAuthorizationPaths $instance;

    public static function GetInstance() : TAuthorizationPaths {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function isAuthenticated() : bool {
        return self::GetInstance()->isAuthenticated;
    }

    public static function isAdmin() : bool {
        return self::GetInstance()->isAdmin;
    }

    public function __construct($roles = null) {
        if (empty($roles)) {
            $user = TUser::getCurrent();
            $this->isAdmin = $user->isAdmin();
            $this->isAuthenticated = $user->isAuthenticated();
            $this->roles = $user->getRoles();
        }
        else {
            // for testing
            $this->isAuthenticated = !in_array('guest', $roles);
            $this->isAdmin = in_array('administrator', $roles);
            $this->roles = $roles;
        }

        if (!$this->isAdmin) {
            $this->getAccessPaths();
            $this->siteUrl = TWebSite::GetSiteUrl();
        }
    }

    public $isAdmin = false;

    public $isAuthenticated = false;
    private $map = [];

    private $accessPaths;
    private $roles;
    private $siteUrl;
    private function getAccessPaths() {
        if (empty($_SESSION[self::ACCESS_LIST_SESSIONKEY])) {
            $repository = new AccessPathsRepository();
            $result = $repository->getAccessPathRoles();
            $_SESSION[self::ACCESS_LIST_SESSIONKEY] = $result;
            $this->accessPaths = $result;
        }
        else {
            $this->accessPaths = $_SESSION[self::ACCESS_LIST_SESSIONKEY];
        }
    }
    public function isAuthorized($path)
    {
        if ($this->isAdmin) {
            return true;
        }

        if (str_starts_with($path, $this->siteUrl)) {
            $path = substr($path, strlen($this->siteUrl));
        }
        if (str_starts_with($path, '/')) {
            $path = substr($path, 1);
        }
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }
        if (empty($path)) {
            // home page, access for all
            return true;
        }

        $parts = explode('/', $path);
        $count = count($parts);
        if ($count && $parts[0] === $this->siteUrl) {
            array_shift($parts);
        }
        for ($i = 1; $i < $count; $i++) {
            $parts[$i] = $parts[$i - 1] . '/' . $parts[$i];
        }

        $authorized = true;
        foreach ($parts as $dir) {
            $mapped = $this->map[$dir] ?? null;
            if ($mapped !== null) {
                $authorized = $mapped;
            }
            else {
                $filtered = array_filter($this->accessPaths, fn($key) => str_starts_with($key, $dir), ARRAY_FILTER_USE_KEY);
                foreach ($filtered as $authRoles) {
                    if (!empty($authRoles)) {
                        $authorized = !empty(array_intersect($this->roles, $authRoles));
                    }
                }
            }
            if ($authorized === false) {
                break;
            }
        }
        return $authorized;
    }

    public function testAccessPaths() {
        $this->getAccessPaths();
        return $this->accessPaths;
    }

}