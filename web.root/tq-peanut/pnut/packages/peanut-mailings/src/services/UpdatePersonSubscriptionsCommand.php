<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/24/2019
 * Time: 9:57 AM
 */

namespace Peanut\PeanutMailings\services;

use Peanut\PeanutMailings\sys\SubscriptionManager;
use Tops\services\TServiceCommand;
use Tops\sys\TConfiguration;

/**
 * Class UpdatePersonSubscriptionsCommand
 * @package Peanut\Mailings\services
 *
 *  Service contract
 *      Request:
 *          interface IUpdateSubscriptionsRequest {
 *              emailSubscriptions : any[];
 *              postalSubscriptions : any[];
 *              personId : any;
 *              addressId :any;
 *      }
 */
class UpdatePersonSubscriptionsCommand extends TServiceCommand
{

    protected function run()
    {
        $request = $this->getRequest();
        if (empty($request)) {
            $this->addErrorMessage('service-invalid-request');
            return;
        }

        if (isset($request->emailSubscriptions)) {
            $personId = $request->personId ?? null;
            if ($personId === null) {
                $this->addErrorMessage('service-error-no-personid');
                return;
            }
        }

        $manager = SubscriptionManager::getInstance();
        $manager->updateEmailSubscriptions($personId,$request->emailSubscriptions);

        $postalSubscriptionsSupported = TConfiguration::getBoolean('postalSubscriptions','features');
        if ($postalSubscriptionsSupported) {
            if (isset($request->postalSubscriptions)) {
                $addressId = isset($request->addressId) ? $request->addressId : null;
                if ($addressId === null) {
                    $this->addErrorMessage('service-error-no-addressid');
                    return;
                }
            }
            $manager->updatePostalSubscriptions($addressId,$request->postalSubscriptions);
        }
        // todo: plan notification strategy
        /*
        if (isset($request->notifications)) {
            if ($request->notifications == 0) {
                $manager->disableNotifications($personId);
            }
            else {
                $manager->enableNotifications($personId);
            }
        }
        */
        $this->addInfoMessage('service-message-subscriptions-updated');
    }
}
