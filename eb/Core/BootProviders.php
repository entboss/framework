<?php

namespace Eb\Core;

use Eb\Contract\Application;

class BootProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Eb\Contracts\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $app->boot();
    }
}
