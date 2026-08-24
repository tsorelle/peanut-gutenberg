<?php
/**
 * Created by /tools/create-model.php
 * Time:  2022-05-09 21:42:28
 */

namespace Peanut\users\db\model\repository;

use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use \Tops\db\TEntityRepository;

class RolesRepository extends \Tops\db\TEntityRepository
{
    public function getRoleByName($roleName)
    {
        return $this->getSingleEntity('name = ?',$roleName);
    }

    public function getRolesLookup()
    {
        $sql = 'SELECT   `id`,  `name`,  `name` AS `code`,  `description`,  `active` '.
        'FROM   `pnut_roles` WHERE `active` = 1';
        $stmt = $this->executeStatement($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    protected function getTableName() {
        return 'pnut_roles';
    }

    protected function getDatabaseId() {
        return null;
    }

    protected function getClassName() {
       return 'Peanut\users\db\model\entity\Role';
    }

    protected function getFieldDefinitionList()
    {
        return array(
        'id'=>PDO::PARAM_INT,
        'name'=>PDO::PARAM_STR,
        'description'=>PDO::PARAM_STR,
        'createdby'=>PDO::PARAM_STR,
        'createdon'=>PDO::PARAM_STR,
        'changedby'=>PDO::PARAM_STR,
        'changedon'=>PDO::PARAM_STR,
        'active'=>PDO::PARAM_STR);
    }
}
