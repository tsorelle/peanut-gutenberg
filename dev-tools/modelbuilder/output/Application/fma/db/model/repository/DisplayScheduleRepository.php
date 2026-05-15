<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2025-04-28 21:18:03
 */ 
namespace Application\fma\db\model;


use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use \Tops\db\TEntityRepository;
use Application\fma\db\model\entity\DisplayScheduleEntry;

class DisplayScheduleRepository extends \Tops\db\TEntityRepository
{
    protected function getTableName() {
        return 'qnut_display_schedule';
    }

    protected function getDatabaseId() {
        return null;
    }

    protected function getClassName() {
         return 'Application\fma\db\model\entity\DisplayScheduleEntry';
    }

    protected function getFieldDefinitionList()
    {
        return array(
        'id'=>PDO::PARAM_INT,
        'path'=>PDO::PARAM_STR,
        'date'=>PDO::PARAM_STR,
        'start'=>PDO::PARAM_STR,
        'end'=>PDO::PARAM_STR,
        'createdby'=>PDO::PARAM_STR,
        'createdon'=>PDO::PARAM_STR,
        'changedby'=>PDO::PARAM_STR,
        'changedon'=>PDO::PARAM_STR,
        'active'=>PDO::PARAM_STR);
    }
}