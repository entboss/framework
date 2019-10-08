<?php

namespace Eb\Controller;

use Eb\Service\UserService;
use Eb\Support\Str;

class WebController extends Controller
{
    public function index() {
        $userSrv = new UserService;
        $user = $userSrv->getUser(1);
        //$uuid = Str::uuid();
        var_dump($user);
        return $this->view('index', $user);
    }
}
