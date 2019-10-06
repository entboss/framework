<?php

namespace Eb\Controller;

use Illuminate\Container\Container;
use Eb\Core\Controller;
use Eb\Model\User;

class WebController extends Controller
{
    public function index(){
        $user = User::first()->toArray();
        //var_dump($user);
        $app = Container::getInstance();
        $factory = $app->make('view');
        return $factory->make('index')->with('item', $user);
    }

}
