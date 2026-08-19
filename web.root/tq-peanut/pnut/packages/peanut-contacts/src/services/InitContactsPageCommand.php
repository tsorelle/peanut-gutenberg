<?php

namespace Peanut\contacts\services;

use Peanut\contacts\db\ContactsManager;
use Peanut\contacts\db\model\entity\Contact;
use Peanut\PeanutMailings\sys\SubscriptionManager;
use Peanut\users\AccountManager;

class InitContactsPageCommand extends \Tops\services\TServiceCommand
{
    /**
     * Service contract:
     * Request : null
     *
     * Response
     *      interface IContactItem {
     *          id : any;
     *          fullname : string;
     *          email : string;
     *          phone : string;
     *          listingtypeId : any;
     *          sortkey : string;
     *          notes : string;
     *          uid : any;
     *          accountId : any;
     *          active : number;
     *          subscriptions : number[] | null;
     *      }
     *
     *      interface IContactInitResponse {
     *          contacts: IContactItem[];
     *          emailLists: Peanut.ILookupItem[];
     *          listingTypes: Peanut.ILookupItem[];
     *      }
     */

    protected function run()
    {
        $response = new \stdClass();
        $manager = new ContactsManager();
        $response->contacts = $manager->getContactList();
        $response->listingTypes = $manager->getListingTypes();

        $subscriptionManager = SubscriptionManager::getInstance();
        $response->emailLists = $subscriptionManager->getEmailSubscriptionList();

        $accounts = new AccountManager();
        $response->roles = $accounts->getRoles();
        $this->setReturnValue($response);
    }
}
