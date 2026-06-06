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
        'postId'=>PDO::PARAM_INT,
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

    public function removeOrphanBlocks($postId,$blockIdList = [])
    {
        if (empty($blockIdList)) {
            $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE postId = ?';
            $stmt = $this->executeStatement($sql, [$postId]);
            return;
        }
        $list = "('". implode("','",$blockIdList)."')";
        $sql = 'DELETE FROM ' . $this->getTableName() .
            ' WHERE postId = ? AND blockId NOT IN '.$list;
        $stmt = $this->executeStatement($sql, [$postId]);
    }

    public function updateBlock($postId,$blockId,$viewModel,$inputValue='')
    {
        $block = $this->getByBlockId($blockId);
        if ($block) {
            if (($block->inputValue == $inputValue) && ($block->viewModel == $viewModel) && ($block->postId == $postId)) {
                return false;
            }
            $block->postId = $postId;
            $block->viewModel = $viewModel;
            $block->inputValue = $inputValue;
            $this->update($block);
        }
        else {
            $block = new PeanutBlock();
            $block->postId = $postId;
            $block->blockId = $blockId;
            $block->viewModel = $viewModel;
            $block->inputValue = $inputValue;
            $this->insert($block);
        }
        return $block;
    }

    public function getByBlockId($blockId) : ?PeanutBlock
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE blockId = ?';
        $stmt = $this->executeStatement($sql,[$blockId]);
        $result = $stmt->fetchObject($this->getClassName());
        if (empty($result)) {
            return null;
        }
        return $result;
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