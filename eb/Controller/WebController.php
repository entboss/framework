<?php

namespace Eb\Controller;

use Eb\Core\Controller;
use Eb\Model\User;
use Illuminate\Container\Container;

class WebController extends Controller
{
    public function index()
    {
        $user = User::first()->toArray();
        //var_dump($user);
        $app = Container::getInstance();
        $factory = $app->make('view');

        return $factory->make('index')->with('item', $user);
    }
}
