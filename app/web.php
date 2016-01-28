<?php

/**
 * 
 * @var \Silex\Application $app
 */
$app = include __DIR__.'/../app/bootstrap.php';

use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\RememberMeServiceProvider;
use Silex\Provider\CsrfServiceProvider;
use Silex\Provider\FormServiceProvider;
use Quazardous\Form\PasswordTypeExtension;

$app->register(new SessionServiceProvider());

$app['session.storage.handler'] = function ($app) {
    // use native session instead of default file session
    return new NativeSessionHandler();
};

$app->register(new CsrfServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new RememberMeServiceProvider());
$app->register(new ServiceControllerServiceProvider());

// You may want to be able to render password value...
$app->extend('form.type.extensions', function ($extensions) use ($app) {
    $extensions[] = new PasswordTypeExtension();
    return $extensions;
});
    

return $app;
