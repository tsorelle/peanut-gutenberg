<?php
namespace PeanutTest\scripts;

use Peanut\PeanutPermissions\db\model\repository\AccessPathsRepository;
use PeanutTest\scripts\TestScript;

class BuildsitemapTest extends TestScript
{
    public function execute()
    {
        $menuName = 'main-menu';
        $builder = new \Tops\cms\wordpress\WordPressSiteMapBuilder();
        $result = $builder->build($menuName);

        $this->assert($result->success, "Builder failed:");
        if ($result->success) {
            print('Output file: ' . $result->outputFile."\n");
        }
        foreach ($result->errors as $error) {
            echo $error . "\n";
        }
    }
}
