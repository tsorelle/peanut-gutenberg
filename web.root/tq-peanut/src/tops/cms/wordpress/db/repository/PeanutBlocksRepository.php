<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2026-06-04 19:36:02
 */
namespace Tops\cms\wordpress\db\repository;

use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use \Tops\db\TEntityRepository;
use \Tops\cms\wordpress\db\entity\PeanutBlock;

class PeanutBlocksRepository extends \Tops\db\TEntityRepository
{
    protected function getTableName() {
        return 'gutn_blocks';
    }

    protected function getDatabaseId() {
        return null;
    }

    protected function getClassName() {
         return 'Tops\cms\wordpress\db\entity\PeanutBlock';
    }

    protected function getFieldDefinitionList()
    {
        return array(
        'id'=>PDO::PARAM_INT,
        'blockId'=>PDO::PARAM_STR,
        'viewModel'=>PDO::PARAM_STR,
        'inputValue'=>PDO::PARAM_STR);
    }

    public function removeBlock($blockId)
    {
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE blockId = ?';
        $stmt = $this->executeStatement($sql,[$blockId]);
        return $stmt->rowCount() > 0;
    }

    public function updateBlock($blockId,$viewModel,$inputValue='')
    {
        $this->removeBlock($blockId);
        $block = new PeanutBlock();
        $block->blockId = $blockId;
        $block->viewModel = $viewModel;
        $block->inputValue = $inputValue;
        $block->id = $this->insert($block);
        return $block;
    }

    public function getBlock($blockId)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE blockId = ?';
        $stmt = $this->executeStatement($sql,[$blockId]);
        $result = $stmt->fetchObject($this->getClassName());
        if (empty($result)) {
            $result = new PeanutBlock();
            $result->blockId = $blockId;
            $result->viewModel = '';
            $result->inputValue = '';
        }
        return $result;
    }
}