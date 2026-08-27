<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/23/2019
 * Time: 3:31 PM
 */

namespace Peanut\PeanutMailings\services;


use Peanut\PeanutMailings\sys\SubscriptionManager;
use Tops\services\TServiceCommand;
use Tops\sys\TConfiguration;

/**
 * Class GetUserSubscriptionsCommand
 * @package Peanut\Mailings\services
 *
 * Service contract
 *   Response
 *    interface IGetSubscriptionsResponse {
 *		personId: any;
 *      personName: string;
 *		addressId: any;
 *		emailLists : Peanut.ILookupItem[];
 *		postalLists : Peanut.ILookupItem[];
 *		emailSubscriptions : ISubscriptionListItem[];
 *		postalSubscriptions : ISubscriptionListItem[];
 *		translations : string[];
 *	  }
 */
class GetUserSubscriptionsCommand extends TServiceCommand
{

    /**
     * @throws \Exception
     */
    protected function run()
    {
        $request = $this->getRequest();
        if (!$request) {
            $this->addErrorMessage('Invalid request');
            return;
        }
        $userId = $request->userId ?? null;
        // if not user id in request and current user is not authenticated, redirect to login page.
        if (!$userId) {
            $user = $this->getUser();
            if (!$user->isAuthenticated()) {
                $response = new \stdClass();
                $response->redirect = TConfiguration::getValue('login-page', 'pages', '/login');
                $this->setReturnValue($response);
                return;
            }
            $userId = $user->getId();
        }
        $subsciptionManager = SubscriptionManager::getInstance();
        $response = $subsciptionManager->getUserSubscriptions($userId);
        if ($response === false) {
            $this->addErrorMessage('Cannot find user account.');
            return;
        }
        $this->setReturnValue($response);
    }
}
