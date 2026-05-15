<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2025-04-07 11:42:44
 */ 

namespace Peanut\QnutDirectory\db\model\entity;

class Person  extends \Tops\db\TEntity 
{ 
    public $id;
    public $firstname;
    public $lastname;
    public $middlename;
    public $fullname;
    public $addressId;
    public $email;
    public $username;
    public $phone;
    public $phone2;
    public $dateofbirth;
    public $junior;
    public $deceased;
    public $listingtypeId;
    public $sortkey;
    public $notes;
    public $active;
    public $uid;
    public $accountId;

    public function getDtoDataTypes()
    {
        $types = parent::getDtoDataTypes();
        $types['dateofbirth'] = \Tops\sys\TDataTransfer::dataTypeDate;
        $types['junior'] = \Tops\sys\TDataTransfer::dataTypeFlag;
        $types['deceased'] = \Tops\sys\TDataTransfer::dataTypeDate;
        return $types;
    }
}
