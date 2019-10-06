<?php

namespace Eb\Controller;

use Eb\Core\Controller;
use Eb\Model\User;

class WebController extends Controller
{
    public function index(){
        $user = User::first();
        var_dump($user);
        
        echo 'controller success.';
    }

}
