<?php

namespace Peanut\PeanutPermissions\db;



use Peanut\PeanutPermissions\db\model\repository\AccessPathsRepository;

class AccessPathManager
{

    public function updatePath($path,$roleNames) {
        $repository = new AccessPathsRepository();
        $repository->updatePath($path,$roleNames);
    }

    public function getAccessPathList() {
        $repository = new AccessPathsRepository();
        $items  = $repository->getAccessPathRoles();
        $result = [];
        foreach ($items as $path => $roles) {
            $item = new \stdClass();
            $item->path = $path;
            $item->roleNames = implode(',',$roles);
            $result[] = $item;
        }
        return $result;
    }

    public function deletePath($path) {
        $repository = new AccessPathsRepository();
        $repository->deletePath($path);
    }

}