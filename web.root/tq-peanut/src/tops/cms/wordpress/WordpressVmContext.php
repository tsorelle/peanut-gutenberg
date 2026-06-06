<?php

namespace Tops\cms\wordpress;

use Peanut\sys\TVmContext;
use Tops\cms\wordpress\db\repository\PeanutBlocksRepository;

class WordpressVmContext extends TVmContext
{
    private PeanutBlocksRepository $repository;
    private function getRepository() : PeanutBlocksRepository {
        if (!isset($this->repository)) {
            $this->repository = new PeanutBlocksRepository();
        }
        return $this->repository;
    }

    protected function getBlockData($blockId): \stdClass
    {
        $result = new \stdClass();
        $blockData = $this->getRepository()->getByBlockId($blockId);
        if (!empty($blockData)) {
            $result->viewmodel = $blockData->viewModel;
            $result->value = $blockData->inputValue;
        }
        return $result;
    }
}