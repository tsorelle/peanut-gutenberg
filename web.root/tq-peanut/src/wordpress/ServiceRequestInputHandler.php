<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/16/2017
 * Time: 5:58 AM
 */

namespace Tops\wordpress;


use Tops\sys\Request;

class ServiceRequestInputHandler extends \Tops\services\ServiceRequestInputHandler
{
    /**
     * @return \Tops\sys\Request
     */
    private function getRequest() {
        return TRequestBuilder::GetRequest();
    }

    /**
     * @return 'POST' | 'GET'
     */
    public function getMethod()
    {
        return $this->getRequest()->getMethod();
    }

    /**
     * @return mixed
     */
    public function get($key)
    {
        return $this->getRequest()->get($key);
    }

    public function getSecurityToken()
    {
        $request = $this->getRequest();
        // return $this->getRequest()->get(\Tops\services\ServiceRequestInputHandler::securityTokenKey);
        $token = $this->getRequest()->get(\Tops\services\ServiceRequestInputHandler::securityTokenKey);
        return $token;
    }
}