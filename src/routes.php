<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', 'HomeController:index')->setName('home');

    // Authentication
    $app->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $app->post('/auth/signup', 'AuthController:postSignUp');

    $app->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $app->post('/auth/signin', 'AuthController:postSignIn');

    $app->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

};
