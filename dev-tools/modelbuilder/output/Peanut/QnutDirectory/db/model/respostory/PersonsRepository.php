<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2025-04-07 11:42:44
 */ 
namespace Peanut\QnutDirectory\db\model;


use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use \Tops\db\TEntityRepository;
use Peanut\QnutDirectory\db\model\entity\Person;

class PersonsRepository extends \Tops\db\TEntityRepository
{
    protected function getTableName() {
        return 'qnut_persons';
    }

    protected function getDatabaseId() {
        return null;
    }

    protected function getClassName() {
         return 'Peanut\QnutDirectory\db\model\entity\Person';
    }

    protected function getFieldDefinitionList()
    {
        return array(
        'id'=>PDO::PARAM_INT,
        'firstname'=>PDO::PARAM_STR,
        'lastname'=>PDO::PARAM_STR,
        'middlename'=>PDO::PARAM_STR,
        'fullname'=>PDO::PARAM_STR,
        'addressId'=>PDO::PARAM_STR,
        'email'=>PDO::PARAM_STR,
        'username'=>PDO::PARAM_STR,
        'phone'=>PDO::PARAM_STR,
        'phone2'=>PDO::PARAM_STR,
        'dateofbirth'=>PDO::PARAM_STR,
        'junior'=>PDO::PARAM_STR,
        'deceased'=>PDO::PARAM_STR,
        'listingtypeId'=>PDO::PARAM_STR,
        'sortkey'=>PDO::PARAM_STR,
        'notes'=>PDO::PARAM_STR,
        'createdby'=>PDO::PARAM_STR,
        'createdon'=>PDO::PARAM_STR,
        'changedby'=>PDO::PARAM_STR,
        'changedon'=>PDO::PARAM_STR,
        'active'=>PDO::PARAM_STR,
        'uid'=>PDO::PARAM_STR,
        'accountId'=>PDO::PARAM_STR);
    }
}