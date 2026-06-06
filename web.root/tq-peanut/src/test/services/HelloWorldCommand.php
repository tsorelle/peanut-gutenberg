<?php
/**
 * Created by PhpStorm.
 * User: terry
 * Date: 5/15/2017
 * Time: 5:02 PM
 */

namespace PeanutTest\services;


use Peanut\sys\TVmContext;
use PHPUnit\Runner\Exception;
use Tops\sys\TLanguage;

class HelloWorldCommand extends \Tops\services\TServiceCommand
{
    protected function run()
    {
        $request = $this->getRequest();
        if (empty($request)) {
            $this->addErrorMessage('No request received.');
            return;
        }
        if (empty($request->tester)) {
            $this->addErrorMessage('Tester name not received.');
            return;
        }

        $contextId = $request->contextId ?? null;
        $context = TVmContext::GetContext($contextId);
        if (empty($context)) {
            $this->addWarningMessage('Context not found.');
        }
        // todo: infomessage not displayed
        $this->addInfoMessage('Hello World from: '.$request->tester);
        $responseValue = new \stdClass();
        $responseValue->message = "Greatings earthlings from ".$request->tester;
        $responseValue->translations =  array(
            'hello' => 'Hola',
            'world' => 'Mundo'
        );
        $this->setReturnValue($responseValue);
        // throw new Exception('Test exception');
    }
}