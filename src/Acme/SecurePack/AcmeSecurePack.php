<?php
namespace Acme\SecurePack;

use Pimple\Container;
use Silex\Application;
use Quazardous\Silex\Pack\JetPackTrait;
use Acme\SecurePack\Controller\DefaultController;
use Quazardous\Silex\Pack\JetPackInterface;

class AcmeSecurePack implements JetPackInterface
{
    // default implementations of some needed functions for the pack interfaces
    use JetPackTrait;

    // a pack is a Silex service provider
    public function register(Container $app)
    {
        // provide your controller as usual
        // To prefix your ids you can use th _ns() function provided by JetPackTrait.
        $app[$this->_ns('controller.default')] = function ($app) {
            return new DefaultController();
        };
    }

    // a pack is a Silex controller provider
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get('/', $this->_ns('controller.default:index'))
            ->bind($this->_ns('index'));
        
        $controllers->get('/secured', $this->_ns('controller.default:secured'))
            ->bind($this->_ns('secured'));
            
        $controllers->get('/secured/admin', $this->_ns('controller.default:admin'))
            ->bind($this->_ns('admin'));
        
        return $controllers;
    }

}
