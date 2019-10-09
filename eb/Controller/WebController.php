<?php

namespace Eb\Controller;

use Eb\Service\UserService;
use Eb\Support\Str;

class WebController extends Controller
{
    public function index() {
        $userSrv = new UserService;
        $user = $userSrv->getUser(1);
        $id = Str::random();
        var_dump($user, $id);
        return $this->view('index', $user);
    }
}
