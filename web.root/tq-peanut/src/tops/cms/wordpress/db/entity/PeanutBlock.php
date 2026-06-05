<?php 
/** 
 * Created by /tools/create-model.php 
 * Time:  2026-06-04 19:36:02
 */ 

namespace Tops\cms\wordpress\db\entity;

class PeanutBlock  extends \Tops\db\TAbstractEntity
{ 
    public $id;
    public $blockId;
    public $viewModel;
    public $inputValue;
    public $postId;
}
