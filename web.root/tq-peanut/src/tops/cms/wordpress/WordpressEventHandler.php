<?php

namespace Tops\cms\wordpress;

use Tops\sys\IContactManager;
use Tops\sys\ISystemEventHandler;
use Tops\sys\TCmsEvents;
use Tops\sys\TConfiguration;
use Tops\sys\TObjectContainer;
use Tops\sys\TSystemEvents;

class WordpressEventHandler extends TCmsEvents
{
    public function handleEvent($eventCode, $argument = null) : bool
    {
        switch ($eventCode) {
            case TSystemEvents::ON_AUTHORIZATIONS_CHANGED:
                $this->onAuthorizationChanged($argument);
                break;
            case TSystemEvents::ON_USER_UPDATE :
                return $this->onUpdateUser($argument);
                break;
            case TSystemEvents::ON_USER_DELETE :
                return $this->onDeleteUser($argument);
            default:
                error_log('Unsupported event: ' . $eventCode);
                return false;
        }
        return true;
    }

    /**
     * @param \WP_User $wpUser
     * @return bool
     */
    private function onUpdateUser($wpUser)
    {
        /**
         * @var IContactManager $contactManager
         */
        $contactManager = TObjectContainer::Get('contact.manager');
        if (!$contactManager) {
            return false;
        }
        $contact = $contactManager->getPersonByAccountId($wpUser->ID);
        $contactId = empty($contact) ? 0 : $contact->id;
        $userContact = new \stdClass();
        $userContact->id = $contactId;
        $userContact->fullname = $wpUser->display_name;
        $userContact->email = $wpUser->user_email;
        $userContact->accountId = $wpUser->ID;
        $userContact->active = 1;
        $contactManager->updateContact($userContact);
        return true;
    }

    private function onDeleteUser($accountId)
    {
        /**
         * @var IContactManager $contactManager
         */
        $contactManager = TObjectContainer::Get('contact.manager');
        if ($contactManager) {
            $contact = $contactManager->getPersonByAccountId($accountId);
            if ($contact) {
                $contact->active = 0;
                $contactManager->updateContact($contact);
            }
        }
        return true;
    }

    private function onAuthorizationChanged($event)
    {
        $menuName = TConfiguration::getValue('menu-name', 'wordpress', $data);
        $builder = new WordPressSiteMapBuilder($menuName);
        $result = $builder->build();
        return !empty($result->success);
    }
}