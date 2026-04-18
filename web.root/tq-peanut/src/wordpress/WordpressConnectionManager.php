<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/15/2017
 * Time: 8:59 AM
 */

namespace Tops\wordpress;


use Tops\db\TConnectionManager;
use Tops\sys\TPath;

class WordpressConnectionManager extends TConnectionManager
{

    private function parseConfigDefinition($line) {
        $line = trim($line);
        if (strlen($line) > 7 && substr($line,0,6) == 'define' ) {
            $parts = explode(');', $line);
            $line = str_replace("'", '', substr($parts[0], 7));
            $parts = explode(',', $line);
            if (sizeof($parts) > 1) {
                $result = new \stdClass();
                $result->key = trim($parts[0]);
                $result->value = trim($parts[1]);
                return $result;
            }
        }
        return false;
    }

    public function getNativeConfiguration()
    {
        $config = new \stdClass();
        $foundCount = 0;
        $configfile = TPath::getFileRoot().'wp-config.php';
        $lines = file($configfile);
        $dbname = '';
        $server = 'localhost';
        foreach($lines as $line) {
            $def = $this->parseConfigDefinition($line);
            if ($def === false) {
                continue;
            }
            switch ($def->key) {
                case 'DB_NAME' :
                    $dbname = $def->value;
                    $foundCount++;
                    break;
                case 'DB_USER' :
                    $config->user = $def->value;
                    $foundCount++;
                    break;
                case 'DB_PASSWORD' :
                    $config->pwd = $def->value;
                    $foundCount++;
                    break;
                case 'DB_HOST' :
                    if ($def->value != 'localhost') {
                        $server = $def->value;
                    }
                    $foundCount++;
                    break;
            }
            if ($foundCount == 4) {
                break;
            }
        }
        if ($foundCount < 3) {
            return false;
        }
        $config->dsn = "mysql:host=$server;dbname=$dbname";
        $result = new \stdClass();
        $result->default = 'wordpress';
        $result->connections = array('wordpress' => $config);
        return $result;
    }
}