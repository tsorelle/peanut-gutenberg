<?php

namespace Peanut\PeanutPermissions;

use PDO;
use Tops\db\TQuery;
use Tops\sys\TUser;
use Tops\sys\TWebSite;

class TAuthorizationPaths
{
    public function __construct($roles = null) {

        if (empty($roles)) {
            $user = TUser::getCurrent();
            $this->isAdmin = $user->isAdmin();
            $this->roles = $user->getRoles();
        }
        else {
            // for testing
            $this->isAdmin = in_array('administrator', $roles);
            $this->roles = $roles;
        }

        if (!$this->isAdmin) {
            $this->getAccessPaths();
            $this->siteUrl = TWebSite::GetSiteUrl();
        }
    }

    public $isAdmin = false;
    private $map = [];

    private $accessPaths;
    private $roles;
    private $siteUrl;
    private function getAccessPaths() {
        $query = new TQuery();
        $sql = 'SELECT p.`uri`,r.`roleName` FROM gutn_accesspaths p JOIN gutn_accessroles r ON r.`pathId` = p.id ORDER BY uri';
        $stmt = $query->executeStatement($sql) ;
        $this->accessPaths = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
    }

    public function isAuthorized($path)
    {
        if ($this->isAdmin) {
            return true;
        }

        if (str_starts_with($path, $this->siteUrl)) {
            $path = substr($path, strlen($this->siteUrl));
        }
        if (empty($path)) {
            // home page, access for all
            return true;
        }

        if (str_starts_with($path, '/')) {
            $path = substr($path, 1);
        }
        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        $parts = explode('/', $path);
        $count = count($parts);
        if ($count && $parts[0] === $this->siteUrl) {
            array_shift($parts);
        }
        for ($i = 1; $i < $count; $i++) {
            $parts[$i] = $parts[$i - 1] . '/' . $parts[$i];
        }

        $authorized = null;
        foreach ($parts as $dir) {
            $mapped = $this->map[$dir] ?? null;
            if ($mapped !== null) {
                $authorized = $mapped;
            }
            $filtered = array_filter($this->accessPaths, fn($key) => str_starts_with($key, $dir), ARRAY_FILTER_USE_KEY);
            foreach ($filtered as $authRoles) {
                if ($authorized === true) {
                    return true;
                }
                $authorized = !empty(array_intersect($this->roles, $authRoles));
            }
        }
        return $authorized ?? true;
    }

    public function testAccessPaths() {
        $this->getAccessPaths();
        return $this->accessPaths;
    }

}