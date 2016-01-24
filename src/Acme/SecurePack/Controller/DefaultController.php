<?php

namespace Acme\SecurePack\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    public function index(Application $app, Request $request)
    {
        $vars = [];
        $vars['user'] = isset($app['user'])?$app['user']:null;
        return $app->renderView('@AcmeSecure/default/index.html.twig', $vars);
    }
    
    public function admin(Application $app, Request $request)
    {
        $vars = [];
        $vars['user'] = isset($app['user'])?$app['user']:null;
        return $app->renderView('@AcmeSecure/default/admin.html.twig', $vars);
    }
    
    public function secured(Application $app, Request $request)
    {
        $vars = [];
        $vars['user'] = isset($app['user'])?$app['user']:null;
        return $app->renderView('@AcmeSecure/default/secured.html.twig', $vars);
    }
}
