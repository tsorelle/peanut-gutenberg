<?php
namespace Peanut\PeanutPermissions\db\model\repository;

use PDO;
use Peanut\PeanutPermissions\db\model\entity\AccessPath;

;

class AccessPathsRepository extends \Tops\db\TEntityRepository
{

    protected function getDatabaseId()
    {
        return null;
    }

    public function getAccessPathRoles() {
        $sql = 'SELECT p.`uri`,r.`roleName` FROM gutn_accesspaths p JOIN gutn_accessroles r ON r.`pathId` = p.id ORDER BY uri';

        $stmt = $this->executeStatement($sql) ;
        return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
    }
    public function getAccessPaths() {
        $sql = 'SELECT p.id, p.`uri` FROM gutn_accesspaths p ORDER BY uri';
        $stmt = $this->executeStatement($sql) ;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deletePath($path)
    {
        $pathObj = $this->getSingleEntity('uri = ?',[$path]);
        if ($pathObj) {
            $sql = 'DELETE from gutn_accessroles WHERE pathId = ?';
            $stmt = $this->executeStatement($sql,[$pathObj->id]);
            $this->delete($pathObj->id);
        }
    }
    public function updatePath($path,$roleNames) {
        $pathId = 0;
        $pathObj = $this->getSingleEntity('uri = ?',[$path]);
        if ($pathObj) {
            $sql = 'DELETE from gutn_accessroles WHERE pathId = ?';
            $stmt = $this->executeStatement($sql,[$pathObj->id]);
            $pathId = $pathObj->id;
        }
        else {
            $pathObj = new AccessPath();
            $pathObj->uri = $path;
            $pathId = $this->insert($pathObj);
        }

        foreach ($roleNames as $role) {
            $sql = 'INSERT INTO gutn_accessroles (pathId,roleName) VALUES (?,?)';
            $stmt = $this->executeStatement($sql,[$pathId,$role]);
        }
    }

    protected function getFieldDefinitionList()
    {
        return array(
            'id'=>PDO::PARAM_INT,
            'uri'=>PDO::PARAM_STR);
    }

    protected function getClassName()
    {
        return 'Peanut\PeanutPermissions\db\model\entity\AccessPath';
    }

    protected function getTableName()
    {
        return 'gutn_accesspaths';
    }
}