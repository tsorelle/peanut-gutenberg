<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 3/30/2017
 * Time: 7:15 AM
 */

namespace Tops\cms\wordpress;


use Tops\sys\IUser;
use Tops\sys\IUserFactory;


class WordpressUserFactory implements IUserFactory
{

    /**
     * @return IUser
     */
    public function createUser()
    {
        return new WordpressUser();
    }

    public function createAccountManager()
    {
        // TODO: Implement createAccountManager() method.
    }
}