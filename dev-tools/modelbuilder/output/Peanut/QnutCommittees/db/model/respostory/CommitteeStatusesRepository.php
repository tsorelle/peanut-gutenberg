<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2025-04-07 11:42:44
 */ 
namespace Peanut\QnutCommittees\db\model;


use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use \Tops\db\TNamedEntitiesRepository;
use Peanut\QnutCommittees\db\model\entity\CommitteeStatus;

class CommitteeStatusesRepository extends \Tops\db\TNamedEntitiesRepository
{
    protected function getTableName() {
        return 'qnut_committee_statuses';
    }

    protected function getDatabaseId() {
        return null;
    }

}