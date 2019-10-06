<?php

$app['router']->get ('/',     'Eb\Controller\WebController@index');

$app['router']->get ('/www', function() {
    return "成功！";
});

$app['router']->post('/', function() {
    return 'POST request!';
});
