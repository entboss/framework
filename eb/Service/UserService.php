<?php
/**
 * UserService
 *
 * @copyright  Copyright (c) 2017 HxCart (http://www.hxcart.com)
 * @license    http://www.hxcart.com/license
 * @author     HxCart Team
 *
 * @version    19.10.8
 */

namespace Eb\Service;

use Eb\Model\User;

class UserService extends Service
{
    public function getUser($id){
        $item = User::find($id)->toArray();
        return $item;
    }

    public function getUsers(){
        $list = User::get()->toArray();
        return $list;
    }
}
