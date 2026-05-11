<?php

namespace Tops\cms\wordpress;

use Tops\services\ServiceRequestInputHandler;

class WordpressInputHandler extends ServiceRequestInputHandler
{

    private function filterValue($value)
    {
        if ($value === null) {
            return null;
        }
        if (is_string($value) && function_exists('wp_unslash')) {
            // fix for wordpress
            return wp_unslash($value);
        }
        return $value;
    }


    /**
     * @inheritDoc
     */
    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        if ($this->getMethod() == 'POST') {
            $value =(is_array($_POST) && isset($_POST[$key])) ? $_POST[$key] : null;
        }
        else {
            $value = (is_array($_GET) && isset($_GET[$key])) ? $_GET[$key] : null;
        }
        return $this->filterValue($value);
    }

    public function getValues($exclude = [])
    {
        $result = new \stdClass();
        foreach ($_POST as $key => $value) {
            if (!array_key_exists($key,$exclude)) {
                $result->$key = $this->filterValue($value);
            }
        }
        foreach ($_GET as $key => $value) {
            if (!array_key_exists($key,$exclude)) {
                if (empty($result->$key)) {
                    $result->$key = $value;
                }
            }
        }
        return $result;

    }

    public function getSecurityToken()
    {
        $result = empty($_GET[ServiceRequestInputHandler::securityTokenKey]) ? null
            : $_GET[ServiceRequestInputHandler::securityTokenKey];
        if (empty($result)) {
            $result = empty($_POST[ServiceRequestInputHandler::securityTokenKey]) ? null
                : $_POST[ServiceRequestInputHandler::securityTokenKey];

        }
        return $this->filterValue($result);
    }
}