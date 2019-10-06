<?php
use Eb\Core\Route;

Route::get ('/',     'Eb\Controller\WebController@index');

Route::get('/fuck', function() {
    echo "成功！";
});

Route::post('/', function() {
    echo 'POST request!';
});
